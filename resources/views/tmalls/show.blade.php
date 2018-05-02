@extends('layouts.app')
@section('title', '天猫抓取')

@section('content')
    <link rel="stylesheet" href="{{asset('libs/loading-master/css/loading.css')}}">
    <link rel="stylesheet" href="{{asset('css/tmall.css')}}">
    <div class="form-horizontal" action="{{route('api.jd.crawler')}}" method="post">
      <div class="form-group">
        <label class="col-sm-2 control-label">天猫网址</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="url" name="url" placeholder="输入天猫产品网址【每天 1:00 和 13:00 从天猫获取一次数据】"/>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" id="sub" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 添加到监控列表</button>
        </div>
      </div>
    </div>
    <div class="col-sm-offset-1 col-sm-10" id="count">

    </div>
    <div class="form-group" >
        <div class="col-sm-4">
            <div class="idTabs" id="idTabs">
                <ul class="nav nav-pills nav-stacked">
                    @foreach($products as $product)
                    <li>
                        <a id="{{$product->id}}" onclick='showChart("{{$product->id}}","{{$product->product}}")' class="item">{{$product->product}}</a>
                        <button type="button" class="btn text-success btn-xs"  onclick="clecounts('{{$product->id}}')">清空</button>
                        <button type="button" class="btn text-danger btn-xs" onclick="destroy('{{$product->id}}')">删除</button>
                        <button onclick="window.open('{{$product->url}}')"  class="btn text-primary btn-xs" >查看</button>
                    </li>
                    @endforeach
                </ul>
            </div>
            {{$products->links()}}
        </div>
        <div class="col-sm-8" >
            <div id="linechart" style="width:100%;height: 300px;"></div>
            <div id="barchart" style="width:100%;height: 330px;"></div>
        </div>

    </div>
@stop

@section('js')
    <script src="{{asset('js/jquery.idTabs.js')}}"></script>
    <script src="{{asset('libs/echarts/echarts.js')}}"></script>
    <script type="text/javascript">
        function showChart(id,pname){
            $('.item').removeClass('selected');
            $('#'+id).addClass('selected');

            var datacounts = new Array();
            var datadates = new Array();
            var datanull = new Array();
            @foreach($products as $product)
                if({{$product->id}}== id ){
                    @foreach($product->collectCounts as $ccount)
                        datacounts.push("{{$ccount->collect_count}}");
                        datadates.push("{{$ccount->count_date}}");
                        datanull.push(0);
                    @endforeach
                }
            @endforeach

            var optionline = {
                title: [{
                    left: 'center',
                    text: pname+' 收藏数折线图(单位:个)'
                }],
                tooltip: {
                    trigger: 'axis'
                },
                xAxis: [{
                    data: datadates
                }],
                yAxis: [{
                    splitLine: {show: true}
                }],
                series: [{
                    type: 'line',
                    showSymbol: true,
                    data: datacounts
                }],
                // dataZoom: [{
                //     type: 'inside',
                //     start: 0,
                //     end: 100
                // }, {
                //     start: 0,
                //     end: 10,
                //     handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
                //     handleSize: '80%',
                //     handleStyle: {
                //         color: '#fff',
                //         shadowBlur: 3,
                //         shadowColor: 'rgba(0, 0, 0, 0.6)',
                //         shadowOffsetX: 2,
                //         shadowOffsetY: 2
                //     }
                // }]
            };

            var optionbar = {
                title: [{
                    left: 'center',
                    text: pname+' 收藏数柱状图(单位:个)'
                }],
                tooltip : {
                    trigger: 'axis',
                    axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                        type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                    },
                    formatter: function (params) {
                        var tar = params[1];
                        return tar.name + '<br/>' + tar.seriesName + ' : ' + tar.value;
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '40',
                    containLabel: true
                },
                xAxis: {
                    type : 'category',
                    splitLine: {show:false},
                    data : datadates
                },
                yAxis: {
                    type : 'value'
                },
                series: [
                    {
                        name: '辅助',
                        type: 'bar',
                        stack:  '总量',
                        itemStyle: {
                            normal: {
                                barBorderColor: 'rgba(0,0,0,0)',
                                color: 'rgba(0,0,0,0)'
                            },
                            emphasis: {
                                barBorderColor: 'rgba(0,0,0,0)',
                                color: 'rgba(0,0,0,0)'
                            }
                        },
                        data: datanull
                    },
                    {
                        name: '收藏数',
                        type: 'bar',
                        stack: '总量',
                        label: {
                            normal: {
                                show: true,
                                position: 'inside'
                            }
                        },
                        data:datacounts
                    }
                ],
                dataZoom: [{
                    type: 'inside',
                    start: 1,
                    end: 98
                }, {
                    start: 0,
                    end: 100,
                    handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
                    handleSize: '80%',
                    handleStyle: {
                        color: '#fff',
                        shadowBlur: 3,
                        shadowColor: 'rgba(0, 0, 0, 0.6)',
                        shadowOffsetX: 2,
                        shadowOffsetY: 6
                    }
                }]
            };
            var linechart=echarts.init(document.getElementById("linechart"));
            var barchart=echarts.init(document.getElementById("barchart"));
            linechart.setOption(optionline);
            barchart.setOption(optionbar);
        }
        showChart("{{$products->first()->id}}","{{$products->first()->product}}");

        $(document).ready(function(){
            $('#idTabs .nav li:first>a').addClass('selected');
            $(".nav").idTabs();

            var dis=$('#idTabs').offset().top;
            $(window).scroll(function() {
                if($(this).scrollTop()>=dis){
                    var topjs=$(this).scrollTop()-dis;
                     document.getElementById("#idTabs").style.top=topjs+"px";
                }else if($(this).scrollTop()<dis){
                    document.getElementById("#idTabs").style.top="0px";
                }
            });
        });
        function clecounts(id){
            layer.confirm('确定要清空所有采集的数据吗', {
              btn: ['否','确定'] //按钮
            }, function(index){
              layer.close(index);
            }, function(){
                $.post('{{url("tmallproduct")}}/'+id,{'id':id,'_method':'PATCH','_token':"{{csrf_token()}}"},function(res){
                    if(res=='success'){
                        layer.msg('清空成功');
                        window.location.href='{{route("tmallproduct.index")}}';
                    }
                    else{
                        layer.alert('清空失败', {
                          skin: 'layui-layer-molv' //样式类名
                          ,closeBtn: 0
                        });
                    }
                });
            });
        }
        function destroy(id){
            layer.confirm('确定要删除吗', {
              btn: ['否','确定'] //按钮
            }, function(index){
              layer.close(index);
            }, function(){
                $.post('{{url("tmallproduct")}}/'+id,{'id':id,'_method':'DELETE','_token':"{{csrf_token()}}"},function(res){
                    if(res=='success'){
                        layer.msg('删除成功');
                        window.location.href='{{route("tmallproduct.index")}}';
                    }
                    else{
                        layer.alert('删除失败', {
                          skin: 'layui-layer-molv' //样式类名
                          ,closeBtn: 0
                        });
                    }
                });
            });
        }

        $('#sub').click(function(){
            var url = $('#url').val();
            var re = /&id=\d*&/;
            var re2 = /\?id=\d*&/;
            var re_num=/\d+/;
            try{
                var id=url.match(re)[0].match(re_num);
            }
            catch(err){}
            if(!id){
                try{
                    var gid=url.match(re2);
                    var id=gid[0].match(re_num);
                }
                catch(err2){
                    alert(err2);
                    return false;
                }
            }
            layer.prompt({title: '请输入显示在列表中的名称', formType: 2}, function(text, index){
                layer.close(index);
                $.post('{{route('tmallproduct.store')}}',{'skuid':id[0],'url':url,'product':text,'_token':"{{csrf_token()}}"},function(res){
                    if(res=='exist'){
                        layer.alert('该产品已存在', {
                          skin: 'layui-layer-molv' //样式类名
                          ,closeBtn: 0
                        });
                    }
                    else if(res=='success'){
                        layer.msg('已添加到监控列表');
                        window.location.href='{{route("tmallproduct.index")}}';
                    }
                    else if(res=='fail'){
                        layer.alert('添加失败', {
                          skin: 'layui-layer-molv' //样式类名
                          ,closeBtn: 0
                        });
                    }
                    else{
                        layer.alert(res, {
                          skin: 'layui-layer-molv' //样式类名
                          ,closeBtn: 0
                        });
                    }
                });
            });

        });
    </script>
@stop