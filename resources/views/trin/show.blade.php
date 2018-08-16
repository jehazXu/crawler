@extends('layouts.app')
@section('title', '交易指数算交易金额')

@section('content')
    <div class="form-group">
        <div style="background: yellow;padding: 10px;width: 300px;font-weight: bold;color: green">
            计算公式：y = 0.0025x<sup>1.5984</sup>
        </div>
    </div>
      <div class="form-group">
        <label class="control-label">交易指数:</label>
        <div style="width: 500px">
          <input type="number" class="form-control" id="trin"  placeholder="输入交易指数[数字]"/>
        </div>

      </div>
    <div class="form-group">
        <div class="control-label">
            交易金额：<label class="result" style="background: lightblue;padding: 5px;">0</label>
        </div>
    </div>
@stop

@section('js')
    <script src="{{asset('js/jquery.idTabs.js')}}"></script>
    <script type="text/javascript">

        $(function(){
            $('#trin').bind('input porpertychange',function(){
                let res = Math.pow($("#trin").val(),1.5984);
                $('.result').text(Math.round(res*0.0025));
            });
        })
    </script>
@stop