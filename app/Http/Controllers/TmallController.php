<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TmallController extends Controller
{
    public function show(){
        return view('tmalls.show');
    }

    public function create(){

    }

    public function crawler(Request $request){
        $id=$request->id;
        $count=self::collectCount($id[0]);
        return $count;
    }

    static function collectCount($id)
    {
        $url="https://count.taobao.com/counter3?_ksTS=1524548798552_254&callback=jsonp255&keys=SM_368_dsr-2911805119,ICCP_1_".$id;
            $cnt = file_get_contents($url);
            $content= mb_convert_encoding($cnt ,"UTF-8","GBK");

            $content = str_replace("{\"", "{",$content);
            $content = str_replace("\"}", "}",$content);
            $content = str_replace("\":",":",$content);
            $content = str_replace(",\"", ",",$content);

            $content = str_replace("{", "{\"",$content);
            $content = str_replace(":", "\":",$content);
            $content = str_replace(",", ",\"",$content);
            $content = str_replace("}\"}", "}}",$content);

            $data=mb_substr($content,9,-2,'UTF-8');
            $arraydata=json_decode($data, true);
            $count=$arraydata['ICCP_1_'.$id];
            return $count;
    }
}
