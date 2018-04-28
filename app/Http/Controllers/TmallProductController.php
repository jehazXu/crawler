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
        $products=Tmallproduct::with('collectCounts')->paginate(10);
        return view('tmalls.show',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
       $id = DB::table('tmall_products')->insertGetId(['product' => $request->product, 'skuid' => $request->skuid]);
       if($id){
            $collectnum=CollectCount::collectCount($request->skuid);
            $ccount=new CollectCount;
            $ccount->tproduct_id=$id;
            $ccount->collect_count=$collectnum;
            $ccount->count_date=date("Y/m/d");
            $ccount->save();
            return 'success';
       }
       else{
            return 'fail';
       }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\TmallProduct  $tmallProduct
     * @return \Illuminate\Http\Response
     */
    public function show(Tmallproduct $tmallproduct)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\TmallProduct  $tmallProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(Tmallproduct $tmallproduct)
    {
        //
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
