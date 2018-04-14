<?php

namespace App\Http\Controllers;
use QL\QueryList;

use Illuminate\Http\Request;
class JdController extends Controller
{
    public function show(){
        return view('jds.show');
    }

    function crawler(Request $request){
        $res[0]=0;
        $page=$request->page-1;
        $sortType=$request->sorttype;
        $url="https://sclub.jd.com/comment/productPageComments.action?callback=fetchJSON_comment98vv474&productId=".$request->pid."&score=0&sortType=".$sortType."&page=".$page."&pageSize=10&isShadowSku=0&rid=0&fold=1";
        $cnt = file_get_contents($url);
        $content= mb_convert_encoding($cnt ,"UTF-8","GBK");
        $data=mb_substr($content,25,count($content)-3,'UTF-8');
        $jsondata=json_decode($data, true);
        $comments=$jsondata['comments'];
        return json_encode($comments);
    }
}
