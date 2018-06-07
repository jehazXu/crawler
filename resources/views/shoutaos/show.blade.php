@extends('layouts.app')
@section('title', '手淘排名')

@section('content')
    <link rel="stylesheet" href="{{asset('libs/loading-master/css/loading.css')}}">
    <div class="form-horizontal" action="{{route('api.jd.crawler')}}" method="post">
        {{--<div class="form-group">--}}
            {{--<label class="col-sm-2 control-label">掌柜名称</label>--}}
            {{--<div class="col-sm-10">--}}
                {{--<input type="text" class="form-control" id="nick" name="nick" placeholder="输入店铺掌柜名【查找条件，请仔细填写】"/>--}}
            {{--</div>--}}
        {{--</div>--}}
      <div class="form-group">
        <label class="col-sm-2 control-label">产品标题</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="title" name="title" placeholder="输入产品标题【查找条件，请完整填写，两边不要有空格】"/>
        </div>
      </div>
      <div class="form-group">
          <label class="col-sm-2 control-label">搜索关键词</label>
          <div class="col-sm-10">
              <input type="text" class="form-control" id="key" name="key" placeholder="输入搜索关键词"/>
          </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-2">
          <button  id="add" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 添加到列表</button>
        </div>
          <div class=" col-sm-5">
              <button type="submit" id="sub" class="btn btn-success"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> 只看一次</button>
          </div>
      </div>
    </div>
    <div class="form-group" >
        <div class="col-sm-6">
            <div class="idTabs" id="idTabs">
                <ul class="nav nav-pills nav-stacked">
                    @foreach($shoutaos as $shoutao)
                    <li>
                        <a id="{{$shoutao->id}}" onclick='showChart("{{$shoutao->id}}","{{$shoutao->product}}")' class="item">{{$shoutao->title}} <label style="color:red">--【关键词：{{$shoutao->key}}】</label></a>
                        <button type="button" class="btn text-success btn-xs"  onclick="clecounts('{{$shoutao->id}}')">清空</button>
                        <button type="button" class="btn text-danger btn-xs" onclick="destroy('{{$shoutao->id}}')">删除</button>
                        <button onclick='search("{{$shoutao->title}}","{{$shoutao->key}}")'  class="btn text-primary btn-xs" >排名</button>
                    </li>
                    @endforeach
                </ul>
            </div>
            {{$shoutaos->links()}}
        </div>
        <div class="col-sm-6" >
            <div class="alert alert-success" role="alert" style="height: 200px">
                <div id="gettitle" style="margin: 5px">

                </div>
                <div  id="ranking" style="margin:15px 5px;color: red">

                </div>
            </div>
        </div>

    </div>
@stop

@section('js')
    <script src="{{asset('js/jquery.idTabs.js')}}"></script>
    <script src="{{asset('libs/loading-master/js/loading.js')}}"></script>
    <script type="text/javascript">

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
        }

        function destroy(id){
            layer.confirm('确定要删除吗', {
              btn: ['否','确定']
            }, function(index){
              layer.close(index);
            }, function(){
                $.post('{{url("shoutao")}}/'+id,{'id':id,'_method':'DELETE','_token':"{{csrf_token()}}"},function(res){
                    if(res=='success'){
                        layer.msg('删除成功');
                        window.location.href='{{route("shoutao.index")}}';
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

        $('#add').click(function(){
            var title = $('#title').val();
            var key = $('#key').val();
            // var nick = $('#nick').val();
            $.post('{{route('shoutao.store')}}',{'title':title,'key':key,'_token':"{{csrf_token()}}"},function(res){
                if(res=='success'){
                    layer.msg('已添加到列表');
                    window.location.replace('{{route("shoutao.index")}}');
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

        $('#sub').click(function(){
            var title = $('#title').val();
            // var nick = $('#nick').val();
            var key = $('#key').val();
            search(title,key);

        });

        function search(title,key){
            $('body').loading({
                loadingWidth:240,
                title:'查询中...请稍等!',
                name:'loadfram',
                discription:'抓取中...',
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
            $.post('{{route('shoutao.getranking')}}',{'title':title,'key':key,'_token':"{{csrf_token()}}"},function(res){
                removeLoading('loadfram');
                if(res=='fail'){
                    layer.alert('获取失败，或者前100页无排名', {
                        skin: 'layui-layer-molv' //样式类名
                        ,closeBtn: 0
                    });
                }
                else{
                    $('#gettitle').text(title);
                    let page= Math.ceil(res/22);
                    $('#ranking').text('当前排名：【'+res+"】,手淘第【"+page+'】 页');
                }
            });
        }
    </script>
@stop