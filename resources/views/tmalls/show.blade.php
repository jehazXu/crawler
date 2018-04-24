@extends('layouts.app')
@section('title', '天猫抓取')

@section('content')
    <link rel="stylesheet" href="{{asset('libs/loading-master/css/loading.css')}}">
    <link rel="stylesheet" href="{{asset('libs/bootstrap-table-master/dist/bootstrap-table.min.css')}}">
    <div class="form-horizontal" action="{{route('api.jd.crawler')}}" method="post">
      <div class="form-group">
        <label class="col-sm-2 control-label">天猫网址</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="url" name="url" placeholder="输入天猫产品网址"/>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" id="sub" class="btn btn-success"><span class="glyphicon glyphicon-magnet" aria-hidden="true"></span> 抓取数据</button>
        </div>
      </div>
    </div>
    <div class="col-sm-offset-1 col-sm-10" id="count">

    </div>
@stop

@section('js')
    <!-- Latest compiled and minified JavaScript -->
    <script src="{{asset('libs/bootstrap-table-master/dist/bootstrap-table.min.js')}}"></script>
    <!-- Latest compiled and minified Locales -->
    <script src="{{asset('libs/bootstrap-table-master/dist/locale/bootstrap-table-zh-CN.min.js')}}"></script>
    <script src="{{asset('libs/loading-master/js/loading.js')}}"></script>
    <script type="text/javascript">
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