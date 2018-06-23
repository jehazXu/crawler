@extends('layouts.app')
@section('title', '产品分析')

@section('css')
    <style>
        .table-header {
            line-height: 1.5;
            font-size: 1.2em;
        }
    </style>
@endsection

@section('content')
    <link rel="stylesheet" href="{{asset('libs/loading-master/css/loading.css')}}">
    <div class="form-horizontal" action="{{route('api.jd.crawler')}}" method="post">
        <div class="form-group">
            <label class="col-sm-2 control-label">天猫网址</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="url" name="url"
                       value="https://detail.tmall.com/item.htm?spm=a21ag.7634338.0.0.35193dd5v7vc1F&id=40485869565&sku_properties=31480:3236467"
                       placeholder="输入天猫产品网址"/>
            </div>
            <label class="col-sm-2 control-label">cookie</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="cookie" name="cookie" ref="cookie" value="{{$cookie}}"
                       placeholder="输入淘宝cookie"/>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" id="sub" class="btn btn-success">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 添加到分析列表
                </button>
            </div>
        </div>
    </div>
    <div class="col-sm-offset-1 col-sm-10" id="count">
    </div>
    <div class="form-group">
        @if(count($products))
            <div class="col-sm-3">
                <div class="idTabs" id="idTabs">
                    <ul class="nav nav-pills nav-stacked">
                        @foreach($products as $product)
                            <li>
                                <a id="{{$product->id}}" @click='getMsg({{$product->id}})'
                                   class="item">{{$product->name}}</a>
                                <button type="button" class="btn text-success btn-xs"
                                        @click='updateMsg({{$product->id}})'>最新
                                </button>
                                <button type="button" class="btn text-danger btn-xs"
                                        @click='delPro({{$product->id}})'>删除
                                </button>
                                <button onclick="window.open('{{$product->url}}')" class="btn text-primary btn-xs">商品
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
                {{--                {{$products->links()}}--}}
            </div>
        @endif
        <div class="col-sm-9 table-responsive">
            <table v-if="items" class="table table-bordered table-striped table-hover bg-light" style="font-size: 1px">
                <div class="table-header text-center">@{{items[0]?items[0].created_at:''}}</div>
                <tr>
                    <th style="width: 130px">关键字</th>
                    <th>当日流量</th>
                    <th>浏览量</th>
                    <th>浏览占比</th>
                    <th>店内跳转数</th>
                    <th>跳出本店数</th>
                    <th>收藏数</th>
                    <th>加购数</th>
                    <th>下单数</th>
                    <th>转化率</th>
                    <th>支付件数</th>
                    <th>支付人数</th>
                    <th>转化率</th>
                </tr>
                <tr v-for="item in items">
                    <td>@{{ item.keyword }}</td>
                    <td>@{{ item.uv }}</td>
                    <td>@{{ item.pv_value }}</td>
                    <td>@{{ item.pv_ratio }}</td>
                    <td>@{{ item.bounce_self_uv }}</td>
                    <td>@{{ item.bounce_uv }}</td>
                    <td>@{{ item.clt_cnt }}</td>
                    <td>@{{ item.cart_byr_cnt }}</td>
                    <td>@{{ item.crt_byr_cnt }}</td>
                    <td>@{{ item.crt_rate }}</td>
                    <td>@{{ item.pay_itm_cnt }}</td>
                    <td>@{{ item.pay_byr_cnt }}</td>
                    <td>@{{ item.pay_rate }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection

@section('js')
    {{--<script src="https://cdn.bootcss.com/vue/2.5.16/vue.js"></script>--}}
    <script src="https://cdn.bootcss.com/axios/0.18.0/axios.js"></script>
    <script src="{{asset('js/jquery.idTabs.js')}}"></script>
    <script src="{{asset('libs/echarts/echarts.js')}}"></script>

    <script>
        var log = console.log.bind(console);
        var app = new Vue({
            el: '#app',
            data: {
                items: '',
            },
            methods: {
                getMsg: function (id) {
                    axios.get('/productanalys/' + id)
                        .then((response) => {
                            this.items = response.data;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                },
                updateMsg: function (id) {
                    axios.patch('/productanalys/' + id, {cookie: this.$refs.cookie.value})
                        .then((response) => {
                            console.log(response);
                            switch (response.data) {
                                case 'cookie'://cookie失效
                                    log(cookie);
                                    layer.msg('cookie失效请更新cookie');
                                    window.location.href = '{{route("productanalys.index")}}';
                                    break;
                                default:
                                    layer.msg('更新成功');
                                    this.items = response.data;
                                    break;
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                },
                delPro: function (id) {
                    axios.delete('/productanalys/' + id)
                        .then((response) => {
                            switch (response.data) {
                                case 'success':
                                    layer.msg('删除成功');
                                    window.location.href = '{{route("productanalys.index")}}';
                                    break;
                                default:
                                    layer.msg('删除失败');
                                    break;

                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            }
        })
    </script>


    <script type="text/javascript">
        $('#sub').click(function () {
            var url = $('#url').val();
            var cookie = $('#cookie').val();
            var re = /&id=\d*/;
            var re2 = /\?id=\d*/;
            var re_num = /\d+/;
            try {
                var id = url.match(re)[0].match(re_num);
            }
            catch (err) {
            }
            if (!id) {
                try {
                    var gid = url.match(re2);
                    var id = gid[0].match(re_num);
                }
                catch (err2) {
                    alert(err2);
                    return false;
                }
            }
            layer.prompt({title: '请输入显示在列表中的名称', formType: 2}, function (text, index) {
                layer.close(index);
                $.post('{{route('productanalys.store')}}', {
                    'skuid': id[0],
                    'url': url,
                    'product': text,
                    'cookie': cookie,
                    '_token': "{{csrf_token()}}"
                }, function (res) {

                    switch (res) {
                        case 'exist':
                            layer.alert('该产品已存在', {
                                skin: 'layui-layer-molv' //样式类名
                                , closeBtn: 0
                            });
                            break;
                        case 'success':
                            layer.msg('已添加到监控列表');
                            window.location.href = '{{route("productanalys.index")}}';
                            break;
                        case 'fail':
                            layer.alert('添加失败', {
                                skin: 'layui-layer-molv' //样式类名
                                , closeBtn: 0
                            });
                            break;
                        case 'cookie':
                            layer.alert('cookie失效请更新cookie', {
                                skin: 'layui-layer-molv' //样式类名
                                , closeBtn: 0
                            });
                            window.location.href = '{{route("productanalys.index")}}';
                            break;
                        default:
                            layer.alert(res, {
                                skin: 'layui-layer-molv' //样式类名
                                , closeBtn: 0
                            });
                            break;
                    }
                });
            });

        });
    </script>

    {{--<script src="https://sycm.taobao.com/flow/new/item/source/detail.json?itemId=43177306406&dateType=day&dateRange=2018-06-17%7C2018-06-17&pageId=23.s1150&pPageId=23&pageLevel=2&childPageType=se_keyword&page=1&pageSize=100&order=desc&orderBy=uv&device=2&_=1529478971975&token=81deecc55"></script>--}}
@endsection
