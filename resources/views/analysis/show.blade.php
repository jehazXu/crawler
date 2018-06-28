@extends('layouts.app') 
@section('title', '产品分析') 
@section('css')
<link rel="stylesheet" href="{{asset('css/loding.css')}}">
<style>
    .table-header {
        line-height: 1.5;
        font-size: 1.2em;
    }

    [v-cloak] {
        display: none !important;
    }
    #loding {
        position: absolute;
        z-index: 99999;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        background: rgba(5, 5, 5, 0.2);
    }
</style>
@endsection
 
@section('content')
<loding-bar :show='show' ></loding-bar>
<div class="form-horizontal" action="{{route('api.jd.crawler')}}" method="post">
    <div class="form-group">
        <label class="col-sm-2 control-label">天猫网址</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="url" name="url" ref="pro_url" value="" placeholder="输入天猫产品网址" />
        </div>
        <label class="col-sm-2 control-label">关键字</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="keyword" name="keyword" ref="keyword" value="" placeholder="输入关键字" />
        </div>
        <label class="col-sm-2 control-label">cookie</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="cookie" name="cookie" ref="cookie" value="{{$cookie}}" placeholder="输入淘宝cookie"
            />
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" id="sub" class="btn btn-success pull-left" @click="create()">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 添加到分析列表
            </button>
            <div class="input-group col-sm-4">
                <div class="input-group-addon">开始时间</div>
                </label>
                <input type="date" ref='str_time' max="{{Carbon\Carbon::yesterday()->toDateString()}}" value="{{Carbon\Carbon::yesterday()->toDateString()}}"
                    class="form-control">
                <div class="input-group-addon">结束时间</div>
                </label>
                <input type="date" ref='end_time' max="{{Carbon\Carbon::yesterday()->toDateString()}}" value="{{Carbon\Carbon::yesterday()->toDateString()}}"
                    class="form-control">
            </div>
        </div>
    </div>
</div>
<div c lass="col-sm-offset-1 col-sm-10" id="count">
</div>
<div class="form-group" v-cloak>
    @if(count($products))
    <div class="col-sm-4">
        <div class="idTabs" id="idTabs">
            <ul class="nav nav-pills nav-stacked">
                @foreach($products as $product)
                <li>
                    <a id="{{$product->id}}" @click='getMsg({{$product->id}})' class="item">
                        {{$product->name}}
                        <div class="text-danger small">关键词 : {{$product->keyword}}</div>
                        <div class="text-success small">{{$product->str_time}} : {{$product->end_time}}</div>
                    </a>
                    <button type="button" class="btn text-success btn-xs" @click='updateMsg({{$product->id}})'>重新获取
                    </button>
                    <button type="button" class="btn text-danger btn-xs" @click='delPro({{$product->id}})'>删除
                    </button>
                    <button onclick="window.open('{{$product->url}}')" class="btn text-primary btn-xs">商品
                    </button>
                </li>
                @endforeach
            </ul>
        </div>
        {{$products->links()}}
    </div>

    <div class="col-sm-8 table-responsive">
        <div class="form-check" style="height: 60px">
            <label class="form-check-label small">
                <input type="checkbox" class="form-check-input" v-model="keys.keyword" :checked="keys.keyword">&nbsp;关键字&nbsp;&nbsp;&nbsp;</label>
            <label class="form-check-label small">
                <input type="checkbox" class="form-check-input" v-model="keys.uv" :checked="keys.uv">&nbsp;当日流量&nbsp;&nbsp;&nbsp;</label>
            <label class="form-check-label small">
                <input type="checkbox" class="form-check-input" v-model="keys.pv_value" :checked="keys.pv_value">&nbsp;浏览量&nbsp;&nbsp;&nbsp;</label>
            <label class="form-check-label small">
                <input type="checkbox" class="form-check-input" v-model="keys.pv_ratio" :checked="keys.pv_ratio">&nbsp;浏览占比&nbsp;&nbsp;&nbsp;</label>
            <label class="form-check-label small">
                <input type="checkbox" class="form-check-input" v-model="keys.bounce_self_uv" :checked="keys.bounce_self_uv">&nbsp;店内跳转数&nbsp;&nbsp;&nbsp;</label>
            <label class="form-check-label small">
                <input type="checkbox" class="form-check-input" v-model="keys.bounce_uv" :checked="keys.bounce_uv">&nbsp;跳出本店数&nbsp;&nbsp;&nbsp;</label>
            <label class="form-check-label small">
                <input type="checkbox" class="form-check-input" v-model="keys.clt_cnt" :checked="keys.clt_cnt">&nbsp;收藏数&nbsp;&nbsp;&nbsp;</label>
            <label class="form-check-label small">
                <input type="checkbox" class="form-check-input" v-model="keys.cart_byr_cnt" :checked="keys.cart_byr_cnt">&nbsp;加购数&nbsp;&nbsp;&nbsp;</label>
            <label class="form-check-label small">
                <input type="checkbox" class="form-check-input" v-model="keys.crt_byr_cnt" :checked="keys.crt_byr_cnt">&nbsp;下单数&nbsp;&nbsp;&nbsp;</label>
            <label class="form-check-label small">
                <input type="checkbox" class="form-check-input" v-model="keys.crt_rate" :checked="keys.crt_rate">&nbsp;下单转化率&nbsp;&nbsp;&nbsp;</label>
            <label class="form-check-label small">
                <input type="checkbox" class="form-check-input" v-model="keys.pay_itm_cnt" :checked="keys.pay_itm_cnt">&nbsp;支付件数&nbsp;&nbsp;&nbsp;</label>
            <label class="form-check-label small">
                <input type="checkbox" class="form-check-input" v-model="keys.pay_byr_cnt" :checked="keys.pay_byr_cnt">&nbsp;支付人数&nbsp;&nbsp;&nbsp;</label>
            <label class="form-check-label small">
                <input type="checkbox" class="form-check-input" v-model="keys.pay_rate" :checked="keys.pay_rate">&nbsp;支付转化率&nbsp;&nbsp;&nbsp;</label>
        </div>
        <table v-if="items" class="table table-bordered table-striped table-hover bg-light">

            <div class="table-header text-center">@{{items[0]?items[0].created_at:''}}</div>
            <tr>
                <th v-show="true">日期</th>
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
                <td v-show="keys.keyword">@{{ item.day }}</td>
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
        <div>
            <div ref="uv" id="uv" style="height:300px;" v-show="keys.uv"></div>
            <div ref="pv_value" id="pv_value" style="height:300px;" v-show="keys.pv_value"></div>
            <div ref="pv_ratio" id="pv_ratio" style="height:300px;" v-show="keys.pv_ratio"></div>
            <div ref="bounce_self_uv" id="bounce_self_uv" style="height:300px;" v-show="keys.bounce_self_uv"></div>
            <div ref="bounce_uv" id="bounce_uv" style="height:300px;" v-show="keys.bounce_uv"></div>
            <div ref="clt_cnt" id="clt_cnt" style="height:300px;" v-show="keys.clt_cnt"></div>
            <div ref="cart_byr_cnt" id="cart_byr_cnt" style="height:300px;" v-show="keys.cart_byr_cnt"></div>
            <div ref="crt_byr_cnt" id="crt_byr_cnt" style="height:300px;" v-show="keys.crt_byr_cnt"></div>
            <div ref="crt_rate" id="crt_rate" style="height:300px;" v-show="keys.crt_rate"></div>
            <div ref="pay_itm_cnt" id="pay_itm_cnt" style="height:300px;" v-show="keys.pay_itm_cnt"></div>
            <div ref="pay_byr_cnt" id="pay_byr_cnt" style="height:300px;" v-show="keys.pay_byr_cnt"></div>
            <div ref="pay_rate" id="pay_rate" style="height:300px;" v-show="keys.pay_rate"></div>
        </div>
        @endif
    </div>
</div>
</div>
@endsection
 
@section('js')
<script src="https://cdn.bootcss.com/axios/0.18.0/axios.js"></script>
<script src="https://cdn.bootcss.com/vue/2.5.16/vue.js"></script>
<script src="{{asset('libs/echarts/echarts.js')}}"></script>
<script>

    var log = console.log.bind(console);

    //loding组件
    Vue.component('loding-bar', {
        props: ['show'],
        template: '<div id="loding" v-show="show"><div class="windows8"><div class="wBall" id="wBall_1"><div class="wInnerBall"></div></div><div class="wBall" id="wBall_2"><div class="wInnerBall"></div></div><div class="wBall" id="wBall_3"><div class="wInnerBall"></div></div><div class="wBall" id="wBall_4"><div class="wInnerBall"></div></div><div class="wBall" id="wBall_5"><div class="wInnerBall"></div></div></div></div>'
    })

    var app = new Vue({
        el: '#app',
        data: {
            show: false,
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
            },
            names: {
                "uv": "当日流量",
                "pv_value": "浏览量",
                "pv_ratio": "浏览占比",
                "bounce_self_uv": "店内跳转数",
                "bounce_uv": "跳出本店数",
                "clt_cnt": "收藏数",
                "cart_byr_cnt": "加购数",
                "crt_byr_cnt": "下单数",
                "crt_rate": "下单转化率",
                "pay_itm_cnt": "支付件数",
                "pay_byr_cnt": "支付人数",
                "pay_rate": "支付转化率"
            },
        },
        methods: {
            create: function () {
                let url = this.$refs.pro_url.value;
                let cookie = this.$refs.cookie.value;
                let keyword = this.$refs.keyword.value;
                let str_time = this.$refs.str_time.value;
                let end_time = this.$refs.end_time.value;

                if (!(url && cookie && keyword && str_time && end_time)) {
                    layer.msg('请填写完整');
                    return false;
                }

                id = this.getId(url);
      
                layer.prompt({
                    title: '请输入显示在列表中的名称',
                    formType: 2
                },  (text, index) => {
                    layer.close(index);
                    //开始loding
                    this.show = true;
                    axios.post("{{route('productanalys.store')}}", {
                        'skuid': id[0],
                        'url': url,
                        'product': text,
                        'cookie': cookie,
                        'keyword': keyword,
                        'str_time': str_time,
                        'end_time': end_time
                    }).then((response) => {
                        //结束loding
                        this.show = false;
                        switch (response.data) {
                            case 'success':
                                layer.msg('已添加到监控列表');
                                window.location.href = "{{route('productanalys.index')}}";
                                break;
                            case 'fail':
                                layer.alert('添加失败', {
                                    skin: 'layui-layer-molv', //样式类名
                                    closeBtn: 0
                                });
                                break;
                            case 'null':
                                layer.alert('没有数据', {
                                    skin: 'layui-layer-molv', //样式类名
                                    closeBtn: 0
                                });
                                break;
                            case 'cookie':
                                layer.alert('cookie失效请更新cookie', {
                                    skin: 'layui-layer-molv', //样式类名
                                    closeBtn: 0
                                });
                                window.setTimeout(function () {
                                    window.location.href = "{{route('productanalys.index')}}";
                                }, 2000);
                                break;
                            default:
                                layer.alert(res, {
                                    skin: 'layui-layer-molv', //样式类名
                                    closeBtn: 0
                                });
                                break;
                        }
                    }).catch(function (error) {
                        //结束loding
                        this.show = false;
                        console.log(error);
                    });

                });
            },
            getId: function (url) {
                let re = /&id=\d*/;
                let re2 = /\?id=\d*/;
                let re_num = /\d+/;
                try {
                    return url.match(re)[0].match(re_num);
                } catch (err) {}


                if (!id) {
                    try {
                        let gid = url.match(re2);
                        return gid[0].match(re_num);
                    } catch (err2) {
                        alert(err2);
                        return false;
                    }
                }
            },
            getMsg: function (id) {
                axios.get('/productanalys/' + id)
                    .then((response) => {
                        this.createCharts(response.data);
                        this.items = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            updateMsg: function (id) {
                //开始loding
                this.show = true;
                axios.patch('/productanalys/' + id, {
                        cookie: this.$refs.cookie.value
                    })
                    .then((response) => {
                        //结束loding
                        this.show = false;
                        switch (response.data) {
                            case 'cookie': //cookie失效
                                layer.msg('cookie失效请更新cookie');
                                break;
                            case 'null':
                                layer.alert('没有数据', {
                                    skin: 'layui-layer-molv', //样式类名
                                    closeBtn: 0
                                });
                                break;
                            case 'fail':
                                layer.alert('更新出错', {
                                    skin: 'layui-layer-molv', //样式类名
                                    closeBtn: 0
                                });
                                break;
                            default:
                                layer.msg('更新成功');
                                this.createCharts(response.data);
                                this.items = response.data;
                                break;
                        }
                    })
                    .catch(function (error) {
                        //结束loding
                        this.show = false;
                        console.log(error);
                    });
            },
            delPro: function (id) {
                axios.delete('/productanalys/' + id)
                    .then((response) => {
                        switch (response.data) {
                            case 'success':
                                layer.msg('删除成功');
                                window.location.href = "{{route('productanalys.index')}}";
                                break;
                            default:
                                layer.msg('删除失败');
                                break;
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            createCharts: function(data) {
                let keys = Object.keys(this.keys);
                keys.shift();
                let list = new Array();
                keys.forEach((key) => {
                    let v = data.map((item)=>{
                        return item[key];
                    });
                    let d = data.map((item)=>{
                        return item['day'];
                    });

                    list[key] = {
                        'value': v,
                        'day': d
                    }

                });
                keys.forEach((key)=>{
                    if(this.keys[key]){
                        // 基于准备好的dom，初始化echarts实例
                        var myChart = echarts.init(this.$refs[key]);

                        //指定图表的配置项和数据
                        var option = {
                            title: {
                                text: this.names[key]
                            },
                            tooltip: {},
                            legend: {
                                data:['??']
                            },
                            xAxis: {
                                type: 'category',
                                data: list[key]['day']
                            },
                            yAxis: {
                                type: 'value'
                            },
                            series: [{
                                data: list[key]['value'],
                                type: 'bar'
                            }]
                        };

                        // 使用刚指定的配置项和数据显示图表。
                        myChart.setOption(option);
                    }
                });

            }
        }
    })

</script>
@endsection