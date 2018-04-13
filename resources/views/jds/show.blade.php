@extends('layouts.app')
@section('title', '京东评价抓取')

@section('content')
  <h1>京东评价抓取</h1>
  <form action="{{route('jd.crawler')}}">
      <input type="hidden" name="csrf_token" value="{{csrf_token()}}">
      <label>网址：</label>
      <input type="text" name="url" placeholder="输入网址" style="width: 200px;" />
      <input type="submit" name="提交"/>
  </form>
@stop