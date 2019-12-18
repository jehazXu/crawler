@extends('layouts.app')
@section('title', '京东评价抓取')

@section('content')
    <link rel="stylesheet" href="{{asset('libs/loading-master/css/loading.css')}}">
    <link rel="stylesheet" href="{{asset('libs/bootstrap-table-master/dist/bootstrap-table.min.css')}}">
    <div class="form-horizontal" action="{{route('api.jd.crawler')}}" method="post">
      <div class="form-group">
        <label class="col-sm-2 control-label">京东产品ID</label>
        <div class="col-sm-10">
          <input type="number"  id="pid" name="pid" class="form-control" style="margin-bottom: 5px;" placeholder="输入京东产品ID" value="10098049369"/>
          <span style="color: #88888888">示例：网址绿色数字 https://item.jd.com/<span style="background: #4AAB6E;color: white">27220648798</span>.html</span>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">搜索用户名</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="nickname" placeholder="请输入用户名（留空时搜索所有用户）"/>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">从第几页搜索</label>
        <div class="col-sm-10">
          <input type="number" class="form-control" style="margin-bottom: 5px;" id="page" value="1" />
          <span style="color: #55555555;">默认为 1，只搜索第一页，0-表示从所有评论中搜索</span>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">评论排序规则</label>
        <div class="col-sm-10">
          <input id="r1" type="radio" value='5' name="sorttype" checked="true" >京东默认推荐排序</input>
          <input id="r2" type="radio" value='6' name="sorttype">时间排序</input>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" id="sub" class="btn btn-success"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> 展现的评论</button>
          <button type="submit2" id="sub2" class="btn btn-success"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> 折叠区评论</button>
        </div>
        </div>
    </div>
    <div class="panel panel-info">
      <!-- Default panel contents -->
      <div class="panel-heading">评论数据</div>
      <!-- Table -->
      <table data-toggle="table">
            <thead>
                <tr class="">
                    <th>用户名</th>
                    <th>评论时间</th>
                    <th>评论内容</th>
                </tr>
            </thead>
            <tbody id="comment-list">
                <!-- <tr>
                    <td>1</td>
                    <td>Item 1</td>
                    <td>$1</td>
                </tr> -->
            </tbody>
        </table>
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
            postToBack(0);
        });
        $('#sub2').click(function(){
            postToBack(1);
        });
        function postToBack(isfolder){
            $('body').loading({
                loadingWidth:240,
                title:'获取中...请稍等!',
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
            $('#comment-list').html('');
            $.post('{{route('api.jd.crawler')}}',{'pid':$('#pid').val(),'nickname':$('#nickname').val(),'page':$('#page').val(),'sorttype':$("input[name='sorttype']:checked").val(),'isfolder':isfolder},function(data){
                removeLoading('loadfram');
                var obj = JSON.parse(data);
                if(obj && obj.length>0){
                    console.log(obj.length);
                    $('#comment-list').html('');
                    for(var i=0;i<obj.length;i++){
                        $('#comment-list').append("<tr><td>"+obj[i]['nickname']+"</td><td>"+obj[i]['creationTime']+"</td><td>"+obj[i]['content']+"</td></tr>");
                    }
                }
                else{
                    alert('没有数据');
                }
            })
        }
    </script>
@stop