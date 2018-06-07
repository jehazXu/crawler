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
            return -1;
        }
        $lists=self::getLists($key,$page);
        foreach ($lists as $list)
        {
            $i++;
            Log::info('title:'.$list['title'].'--'.$title);
            if($list['title']==$title)
            {
                $find=1;
                break;
            }
        }
        if($find)
        {
            Log::error($i);
            return $i;
        }
        else{
            Log::info('page:'.$page);
            if($page%5==0)
            {
                sleep(2);
            }
            $page++;
            return self::ranking($title,$key,$page,$i);
        }
    }

    static function getLists($key,$page){
        $url="https://s.m.taobao.com/search?q=".$key."&search=%E6%8F%90%E4%BA%A4&tab=all&sst=1&n=20&buying=buyitnow&m=api4h5&abtest=17&wlsort=17&page=".$page;
        $cnt = file_get_contents($url);
        $arraydata=json_decode($cnt, true);
        $lists=$arraydata['listItem'];
        return $lists;
    }
    static function mergeLists($key,$page,$oldlists){
        $url="https://s.m.taobao.com/search?q=".$key."&search=%E6%8F%90%E4%BA%A4&tab=all&sst=1&n=20&buying=buyitnow&m=api4h5&abtest=17&wlsort=17&page=".$page;
        $cnt = file_get_contents($url);
        $arraydata=json_decode($cnt, true);
        $lists=$arraydata['listItem'];
        $data=array_merge($oldlists,$lists);
        return $data;
    }

}
