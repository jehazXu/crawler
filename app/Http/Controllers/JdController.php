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
        $url="https://sclub.jd.com/comment/productPageComments.action?callback=fetchJSON_comment98vv474&productId=".$request->pid."&score=0&sortType=6&page=".$page."&pageSize=10&isShadowSku=0&rid=0&fold=1";
        $cnt = file_get_contents($url);
        $content= mb_convert_encoding($cnt ,"UTF-8","GBK");
        $data=mb_substr($content,25,count($content)-3,'UTF-8');
        $jsondata=json_decode($data, true);
        $comments=$jsondata['comments'];
        foreach ($comments as $key => $value) {
            echo '</br>用户名：'.$value['nickname'].'</br>';
            echo '评价时间：'.$value['creationTime'].'</br></br>';
            echo "--------------------------------------".'</br>';
        }
        // var_dump(json_decode($data, true)['comments']);
        exit;

    }

    // function curl_get($url, $gzip=false){
    // 　$curl = curl_init($url);
    // 　curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // 　curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    // 　if($gzip) curl_setopt($curl, CURLOPT_ENCODING, "gzip"); // 关键在这里
    // 　$content = curl_exec($curl);
    // 　curl_close($curl);
    // 　return $content;
    // }
}
