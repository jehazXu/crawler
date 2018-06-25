@extends('layouts.app')
@section('title', '产品分析')

@section('css')
    <style>
        .table-header {
            line-height: 1.5;
            font-size: 1.2em;
        }

        [v-cloak] {
            display: none !important;
        }
    </style>
@endsection

@section('content')
    <div class="form-horizontal" action="{{route('api.jd.crawler')}}" method="post">
        <div class="form-group">
            <label class="col-sm-2 control-label">天猫网址</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="url" name="url" ref="pro_url"
                       value=""
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
                <button type="submit" id="sub" class="btn btn-success" @click="create()">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 添加到分析列表
                </button>
            </div>
        </div>
    </div>
    <div class="col-sm-offset-1 col-sm-10" id="count">
    </div>
    <div class="form-group" v-cloak>
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
                {{$products->links()}}
            </div>
        
        <div class="col-sm-9 table-responsive">
                <div  class="form-check" style="height: 60px">
                        <label class="form-check-label small" ><input type="checkbox" class="form-check-input"  v-model="keys.keyword" :checked="keys.keyword">&nbsp;关键字&nbsp;&nbsp;&nbsp;</label>
                        <label class="form-check-label small" ><input type="checkbox" class="form-check-input"  v-model="keys.uv" :checked="keys.uv">&nbsp;当日流量&nbsp;&nbsp;&nbsp;</label>
                        <label class="form-check-label small" ><input type="checkbox" class="form-check-input"  v-model="keys.pv_value" :checked="keys.pv_value">&nbsp;浏览量&nbsp;&nbsp;&nbsp;</label>
                        <label class="form-check-label small" ><input type="checkbox" class="form-check-input"  v-model="keys.pv_ratio" :checked="keys.pv_ratio">&nbsp;浏览占比&nbsp;&nbsp;&nbsp;</label>
                        <label class="form-check-label small" ><input type="checkbox" class="form-check-input"  v-model="keys.bounce_self_uv" :checked="keys.bounce_self_uv">&nbsp;店内跳转数&nbsp;&nbsp;&nbsp;</label>
                        <label class="form-check-label small" ><input type="checkbox" class="form-check-input"  v-model="keys.bounce_uv" :checked="keys.bounce_uv">&nbsp;跳出本店数&nbsp;&nbsp;&nbsp;</label>
                        <label class="form-check-label small" ><input type="checkbox" class="form-check-input"  v-model="keys.clt_cnt" :checked="keys.clt_cnt">&nbsp;收藏数&nbsp;&nbsp;&nbsp;</label>
                        <label class="form-check-label small" ><input type="checkbox" class="form-check-input"  v-model="keys.cart_byr_cnt" :checked="keys.cart_byr_cnt">&nbsp;加购数&nbsp;&nbsp;&nbsp;</label>
                        <label class="form-check-label small" ><input type="checkbox" class="form-check-input"  v-model="keys.crt_byr_cnt" :checked="keys.crt_byr_cnt">&nbsp;下单数&nbsp;&nbsp;&nbsp;</label>
                        <label class="form-check-label small" ><input type="checkbox" class="form-check-input"  v-model="keys.crt_rate" :checked="keys.crt_rate">&nbsp;下单转化率&nbsp;&nbsp;&nbsp;</label>
                        <label class="form-check-label small" ><input type="checkbox" class="form-check-input"  v-model="keys.pay_itm_cnt" :checked="keys.pay_itm_cnt">&nbsp;支付件数&nbsp;&nbsp;&nbsp;</label>
                        <label class="form-check-label small" ><input type="checkbox" class="form-check-input"  v-model="keys.pay_byr_cnt" :checked="keys.pay_byr_cnt">&nbsp;支付人数&nbsp;&nbsp;&nbsp;</label>
                        <label class="form-check-label small" ><input type="checkbox" class="form-check-input"  v-model="keys.pay_rate" :checked="keys.pay_rate">&nbsp;支付转化率&nbsp;&nbsp;&nbsp;</label>
                    </div>
            <table v-if="items" class="table table-bordered table-striped table-hover bg-light">

                <div class="table-header text-center">@{{items[0]?items[0].created_at:''}}</div>
                <tr>
                    <th v-show="keys.keyword" style="width: 130px">关键字</th>
                    <th v-show="keys.uv">当日流量</th>
                    <th v-show="keys.pv_value">浏览量</th>
                    <th v-show="keys.pv_ratio">浏览占比</th>
                    <th v-show="keys.bounce_self_uv">店内跳转数</th>
                    <th v-show="keys.bounce_uv">跳出本店数</th>
                    <th v-show="keys.clt_cnt">收藏数</th>
                    <th v-show="keys.cart_byr_cnt">加购数</th>
                    <th v-show="keys.crt_byr_cnt">下单数</th>
                    <th v-show="keys.crt_rate">下单转化率</th>
                    <th v-show="keys.pay_itm_cnt">支付件数</th>
                    <th v-show="keys.pay_byr_cnt">支付人数</th>
                    <th v-show="keys.pay_rate">支付转化率</th>
                </tr>
                <tr v-for="item in items">
                    <td v-show="keys.keyword">@{{ item.keyword }}</td>
                    <td v-show="keys.uv">@{{ item.uv }}</td>
                    <td v-show="keys.pv_value">@{{ item.pv_value }}</td>
                    <td v-show="keys.pv_ratio">@{{ item.pv_ratio }}</td>
                    <td v-show="keys.bounce_self_uv">@{{ item.bounce_self_uv }}</td>
                    <td v-show="keys.bounce_uv">@{{ item.bounce_uv }}</td>
                    <td v-show="keys.clt_cnt">@{{ item.clt_cnt }}</td>
                    <td v-show="keys.cart_byr_cnt">@{{ item.cart_byr_cnt }}</td>
                    <td v-show="keys.crt_byr_cnt">@{{ item.crt_byr_cnt }}</td>
                    <td v-show="keys.crt_rate">@{{ item.crt_rate }}</td>
                    <td v-show="keys.pay_itm_cnt">@{{ item.pay_itm_cnt }}</td>
                    <td v-show="keys.pay_byr_cnt">@{{ item.pay_byr_cnt }}</td>
                    <td v-show="keys.pay_rate">@{{ item.pay_rate }}</td>
                </tr>
            </table>
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.bootcss.com/axios/0.18.0/axios.js"></script>
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.js"></script>
    <script>
        var log = console.log.bind(console);


            var app = new Vue({
                el: '#app',
                data: {
                    items: '',
                    keys: {
                        "keyword": true,
                        "uv": true,
                        "pv_value": true,
                        "pv_ratio": true,
                        "bounce_self_uv": true,
                        "bounce_uv": true,
                        "clt_cnt": true,
                        "cart_byr_cnt": true,
                        "crt_byr_cnt": true,
                        "crt_rate": true,
                        "pay_itm_cnt": true,
                        "pay_byr_cnt": true,
                        "pay_rate": true
                    }
                },
                methods: {
                    create: function () {

                        let url = this.$refs.pro_url.value;
                        let cookie = this.$refs.cookie.value;
                        if (!(url && cookie)) {
                            layer.msg('请填写完整');
                            return false;
                        }

                        let re = /&id=\d*/;
                        let re2 = /\?id=\d*/;
                        let re_num = /\d+/;
                        try {
                            var id = url.match(re)[0].match(re_num);
                        }
                        catch (err) {
                        }


                        if (!id) {
                            try {
                                let gid = url.match(re2);
                                id = gid[0].match(re_num);
                            }
                            catch (err2) {
                                alert(err2);
                                return false;
                            }
                        }

                        layer.prompt({title: '请输入显示在列表中的名称', formType: 2}, function (text, index) {
                            layer.close(index);
                            axios.post("{{route('productanalys.store')}}", {
                                'skuid': id[0],
                                'url': url,
                                'product': text,
                                'cookie': cookie
                            }).then((response) => {
                                switch (response.data) {
                                    case 'exist':
                                        layer.alert('该产品已存在', {
                                            skin: 'layui-layer-molv' //样式类名
                                            , closeBtn: 0
                                        });
                                        break;
                                    case 'success':
                                        layer.msg('已添加到监控列表');
                                        window.location.href = "{{route("productanalys.index")}}";
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
                                        window.setTimeout(function () {
                                            window.location.href = '{{route("productanalys.index")}}';
                                        }, 2000);
                                        break;
                                    default:
                                        layer.alert(res, {
                                            skin: 'layui-layer-molv' //样式类名
                                            , closeBtn: 0
                                        });
                                        break;
                                }
                            }).catch(function (error) {
                                console.log(error);
                            });

                        });
                    },
                    getMsg: function (id) {
                        axios.get('/productanalys/' + id)
                            .then((response) => {
                                this.items = response.data;
                            })
                            .catch(function (error) {
                                // console.log(error);
                            });
                    },
                    updateMsg: function (id) {
                        axios.patch('/productanalys/' + id, {cookie: this.$refs.cookie.value})
                            .then((response) => {
                                switch (response.data) {
                                    case 'cookie'://cookie失效
                                        log(cookie);
                                        layer.msg('cookie失效请更新cookie');
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

@endsection
