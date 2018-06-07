<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Log;

class Shoutaoranking extends Model
{
    public function shoutao()
    {
        return $this->belongsTo('App\Model\Shoutao','shoutao_id');
    }

    static function ranking($title,$key,$page,$i){
        $find=0;
        if($page>100){
            return ['msg'=>'fail','ranking'=>-1];
        }
        $lists=self::getLists($key,$page);
        $product='';
        foreach ($lists as $list)
        {
            $listtitle=str_replace(' ', '', $list['name']);
            $title=str_replace(' ', '', $title);
            $i++;
            if($listtitle==$title)
            {
                $product=$list;
                $find=1;
                break;
            }
        }
        if($find)
        {
            return ['msg'=>'success','ranking'=>$i,'product'=>$product];
        }
        else{
            if($page%5==0)
            {
                sleep(1);
            }
            $page++;
            return self::ranking($title,$key,$page,$i);
        }
    }

    static function getLists($key,$page){
        $url="https://s.m.taobao.com/search?q=".$key."&search=%E6%8F%90%E4%BA%A4&tab=all&sst=1&n=20&buying=buyitnow&m=api4h5&abtest=15&wlsort=15&style=list&closeModues=nav%2Cselecthot%2Conesearch&page=".$page;
        $cnt = file_get_contents($url);
        $arraydata=json_decode($cnt, true);
        $lists=$arraydata['listItem'];
        return $lists;
    }

}
