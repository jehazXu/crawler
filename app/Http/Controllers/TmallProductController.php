<?php

namespace App\Http\Controllers;

use App\Model\TmallProduct;
use Illuminate\Http\Request;

class TmallProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=TmallProduct::with('collectCounts')->paginate(10);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\TmallProduct  $tmallProduct
     * @return \Illuminate\Http\Response
     */
    public function show(TmallProduct $tmallProduct)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\TmallProduct  $tmallProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(TmallProduct $tmallProduct)
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
    public function update(Request $request, TmallProduct $tmallProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\TmallProduct  $tmallProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(TmallProduct $tmallProduct)
    {
        //
    }
}
