@extends('layouts.app')
@section('title', '京东评价抓取')

@section('content')
    <link rel="stylesheet" href="{{asset('libs/loading-master/css/loading.css')}}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.12.1/bootstrap-table.min.css">
    <div class="form-horizontal" action="{{route('jd.crawler')}}" method="post">
      <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">京东产品ID</label>
        <div class="col-sm-10">
          <input type="number" class="form-control" id="pid" name="pid" placeholder="输入京东产品ID" value="10561776205">
          <span style="color: #88888888">示例：网址蓝色数字</span><img style="margin-top: 5px;opacity: 0.68;" src="{{asset('images/jd_url_pid.png')}}">
        </div>
      </div>
      <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">页码</label>
        <div class="col-sm-10">
          <input type="number" class="form-control" id="page" name="page" placeholder="页码" value="1">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" id="sub" class="btn btn-default">抓取数据</button>
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.12.1/bootstrap-table.min.js"></script>
    <!-- Latest compiled and minified Locales -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.12.1/locale/bootstrap-table-zh-CN.min.js"></script>
    <script src="{{asset('libs/loading-master/js/loading.js')}}"></script>
    <script type="text/javascript">
        $('#sub').click(function(){

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
            $.post('{{route('api.jd.crawler')}}',{'pid':$('#pid').val(),'page':$('#page').val()},function(data){
                var obj = JSON.parse(data);
                if(obj){
                    removeLoading('test');
                    $('#comment-list').html('');
                    for(var i=0;i<obj.length;i++){
                        $('#comment-list').append("<tr><td>"+obj[i]['nickname']+"</td><td>"+obj[i]['creationTime']+"</td></tr>");
                    }
                }
                else{
                    removeLoading('test');
                    alert('没有数据');
                }
            })
        })
    </script>
@stop