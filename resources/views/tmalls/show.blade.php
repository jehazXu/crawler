@extends('layouts.app')
@section('title', '天猫抓取')

@section('content')
    <link rel="stylesheet" href="{{asset('libs/loading-master/css/loading.css')}}">
    <link rel="stylesheet" href="{{asset('css/tmall.css')}}">
    <div class="form-horizontal" action="{{route('api.jd.crawler')}}" method="post">
      <div class="form-group">
        <label class="col-sm-2 control-label">天猫网址</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="url" name="url" placeholder="输入天猫产品网址"/>
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
                        <a onclick='showChart({{$product->id}},"{{$product->product}}")' class="item selected">{{$product->product}}</a>
                        <button type="button" class="btn text-success btn-xs">编辑</button>
                        <button type="button" class="btn text-danger btn-xs">删除</button>
                    </li>
                    @endforeach
                </ul>
            </div>
            {{$products->links()}}
        </div>
        <div class="col-sm-8" >
            <div id="linechart" style="width:100%;height: 300px;"></div>
            <div id="barchart" style="width:100%;height: 300px;"></div>
        </div>
    </div>
@stop

@section('js')
    <script src="{{asset('libs/loading-master/js/loading.js')}}"></script>
    <script src="{{asset('js/jquery.idTabs.js')}}"></script>
    <script src="{{asset('libs/echarts/echarts.js')}}"></script>
    <script type="text/javascript">
        function showChart(id,pname){
            option = {
                title: {
                    text: pname+' 收藏数量（单位:个）'
                },
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
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: {
                    type : 'category',
                    splitLine: {show:false},
                    data : ['总费用','房租','水电费','交通费','伙食费','日用品数']
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
                        data: [0, 0, 0, 0, 0, 0]
                    },
                    {
                        name: '生活费',
                        type: 'bar',
                        stack: '总量',
                        label: {
                            normal: {
                                show: true,
                                position: 'inside'
                            }
                        },
                        data:[2900, 1200, 300, 200, 900, 300]
                    }
                ]
            };
            var barchart=echarts.init(document.getElementById("barchart"));
            var linechart=echarts.init(document.getElementById("linechart"));
            linechart.setOption(option);
            barchart.setOption(option);
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".nav").idTabs();
            $(".tab-item").show();

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

        $('#sub').click(function(){
            $('body').loading({
                loadingWidth:240,
                title:'获取中,请稍等...!',
                name:'loadfram',
                discription:'数据获取中...',
                direction:'row',
                type:'pic',
                originBg:'#71EA71',
                originDivWidth:60,
                originDivHeight:60,
                originWidth:6,
                originHeight:6,
                smallLoading:false,
                loadingBg:'rgba(20,125,148,0.8)',
                loadingMaskBg:'rgba(123,122,222,0.2)'
            });
            var url = $('#url').val();
            var re = /&id=\d*&/;
            var re_num=/\d+/;
            try{
                var id=url.match(re)[0].match(re_num);
                if(!id) throw '获取产品id失败或网址输入错误！';
            }
            catch(err)
            {
                alert(err);
                removeLoading('loadfram');
                return false;
            }
            $.post('{{route('api.tmall.crawler')}}',{'id':id},function(data){
                removeLoading('loadfram');
                if(data){
                    $("#count").html("<h3>收藏数："+data+"</h3>")
                }
                else{
                    alert('没有数据');
                }
            });
        });
    </script>
@stop