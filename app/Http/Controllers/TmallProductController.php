<?php

namespace App\Http\Controllers;

use App\Model\Tmallproduct;
use App\Model\Collectcount;
use Illuminate\Http\Request;
use DB;

class TmallProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=Tmallproduct::with('collectCounts')->paginate(8);
        return view('tmalls.show',compact('products'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $isexist= Tmallproduct::where('skuid',$request->skuid)->first();
       if($isexist)
       {
            return "exist";
       }
       try{
            $id = DB::table('tmall_products')->insertGetId(['product' => $request->product, 'skuid' => $request->skuid,'url' => $request->url]);
       }
       catch(Exception $e){
            return $e->getMessage();
       }
       if($id){
            $collectnum=CollectCount::collectCount($request->skuid);
            $ccount=new CollectCount;
            $ccount->tproduct_id=$id;
            $ccount->collect_count=$collectnum;
            $ccount->count_date=date("Y-m-d H:i:s");
            $ccount->save();
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
    public function update(Request $request, Tmallproduct $tmallproduct)
    {
        foreach ($tmallproduct->collectCounts as $collectcount) {
            $collectcount->delete();
        }
        return "success";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\TmallProduct  $tmallProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(TmallProduct $tmallproduct)
    {
        $res=$tmallproduct->delete();
        if($res){
            return "success";
        }
        return "fail";
    }
}
