<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Htmldom;

class JdController extends Controller
{
    public function show(){
        return view('jds.show');
    }

    function crawler($url){
        $html = new Htmldom($url);
        // 提取全部的图片src
        foreach($html->find('img') as $element)
            echo $element->src . '<br>';
    }
}
