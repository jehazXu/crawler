@extends('layouts.app')
@section('title', '京东评价抓取')

@section('content')
    <link rel="stylesheet" href="{{asset('libs/loading-master/css/loading.css')}}">
    <h1>京东评价抓取</h1>
    <form action="{{route('jd.crawler')}}" method="post">
      <div class="form-group">
        <label for="exampleInputEmail1">京东产品ID</label>
        <input type="number" class="form-control" name="pid" placeholder="输入产品ID">
      </div>
      <div class="form-group">
        <label for="exampleInputPassword1">页码</label>
        <input type="number" class="form-control" name="page" placeholder="页码" value="1">
      </div>
      <button type="submit" class="btn btn-default">提交</button>
    </form>
    <!-- <div id='comment-list'>
        <input type="text" id="url" name="url" placeholder="输入网址" style="width: 200px;" />
      <input type="button" id="submit2" name="submit" value="提交"/>
    </div> -->
@stop

@section('js')
    <script src="{{asset('libs/loading-master/js/loading.js')}}"></script>
    <!-- <script type="text/javascript">
        $('#submit2').click(function(){

            $('body').loading({
                loadingWidth:240,
                title:'请稍等!',
                name:'test',
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
            // $.post('{{route('jd.crawler')}}',{'url':$('#url').val()},function(data){
            //     var obj = data.parseJSON();
            //     if(obj.res){
            //         removeLoading('test');
            //         for(var i=0;i<obj['url'].length;i++){
            //             $('#comment-list').append("<b>"+obj['url'][i]+"</b>");
            //         }
            //     }
            // })
        })
    </script> -->
@stop