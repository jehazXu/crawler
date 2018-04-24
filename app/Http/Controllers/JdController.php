<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redis;
use QL\QueryList;

use Illuminate\Http\Request;
class JdController extends Controller
{
    public function show(){
        return view('jds.show');
    }

    function crawler(Request $request){
        $sortType=$request->sorttype;
        $pid=$request->pid;
        $page=$request->page;
        $nickname=$request->nickname;
        $array=array();
        if($page==0){
            $arraydata=self::getAllComments($pid,$sortType,0,$array);
        }
        else{
            $arraydata=self::getIndexComments($pid,$sortType,$page-1);
        }
        if(!empty($nickname)){
            $data=self::searchInComments($arraydata,'nickname',$nickname);
            return json_encode($data);
        }
        return json_encode($arraydata);
    }

    static function getAllComments($pid,$type,$page,&$array)
    {
        $arraydata=self::getIndexComments($pid,$type,$page);
        if(count($arraydata)>=10){
            $page++;
            //防止浏览器报429 too many request错误，设置每取20条数据暂停5秒
            if($page%20==0)
            {
                sleep(5);
            }
            //递归下一页
            self::getAllComments($pid,$type,$page,$array);
        }
        if(count($array) && count($arraydata)){
            $array=array_merge($arraydata,$array);
        }
        else{
            $array=$arraydata;
        }
        return $array;
    }

    static function getIndexComments($pid,$type,$page)
    {
        // score表示评论的类型（好评为3 中评为2 差评为1 全部评论为0 追评为5）
        // pageSize是每页最多的评论数（最大为10）
        //京东折叠的评论链接 https://sclub.jd.com/comment/getProductPageFoldComments.action?callback=jQuery7366544&productId=26430850300&score=0&sortType=5&page=0&pageSize=10&_=1524212109913
        $url="https://sclub.jd.com/comment/productPageComments.action?callback=fetchJSON_comment98vv474&productId=".$pid."&score=0&sortType=".$type."&page=".$page."&pageSize=10&isShadowSku=0&rid=0&fold=0";
        $cnt = file_get_contents($url);
        $content= mb_convert_encoding($cnt ,"UTF-8","GBK");
        $data=mb_substr($content,25,-2,'UTF-8');
        $arraydata=json_decode($data, true);
        $array=$arraydata['comments'];
        return $array;
    }

    static function searchInComments($array,$key,$value)
    {
        $res=array();
        //正则判断获取中英文混合式的字符个数而非字符串占位数
        $va=mb_substr($value, 0,1,'utf-8').'***'.mb_substr($value,-1,1,'utf-8');
        foreach($array as $keyp=>$valuep){
            $va2=mb_substr($valuep[$key], 0,1,'utf-8').'***'.mb_substr($valuep[$key],-1,1,'utf-8');
            if($va2==$va){
                $re[$key]=$valuep[$key];
                $re['creationTime']=$valuep['creationTime'];
                array_push($res, $re);
            }
        }
        return $res;
    }

}
