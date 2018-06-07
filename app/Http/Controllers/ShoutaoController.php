<?php

namespace App\Http\Controllers;

use App\Model\Shoutao;
use App\Model\Shoutaoranking;
use Illuminate\Http\Request;
use DB;
use Log;

class ShoutaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shoutaos=Shoutao::with('rankings')->paginate(10);
        return view('shoutaos.show',compact('shoutaos'));
    }

    public function getRanking(Request $request)
    {
        $ranking=Shoutaoranking::ranking($request->title,$request->key,1,0);
        if($ranking==-1)
        {
            return 'fail';
        }
        else{
            Log::info('ranking:'.$ranking);
            return $ranking;
        }
    }

    public function test()
    {
        $rank=Shoutaoranking::GetLists('好想你',1);
        dump($rank);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $id = DB::table('shoutaos')->insertGetId(['title' => $request->title, 'key' => $request->key]);
        }
        catch(Exception $e){
            return $e->getMessage();
        }
        if($id){
            return 'success';
        }
        else{
            return 'fail';
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\TmallProduct  $tmallProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shoutao $shoutao)
    {
        foreach ($shoutao->rankings as $ranking) {
            $ranking->delete();
        }
        return "success";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\TmallProduct  $tmallProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shoutao $shoutao)
    {
        $res=$shoutao->delete();
        if($res){
            return "success";
        }
        return "fail";
    }
}
