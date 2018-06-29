<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\AnalsisInfo;
use App\Model\ProductAnalysis;
use Illuminate\Support\Facades\Validator;

class AnalsisInfosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return 'date';
        }

        $date = $request->input('date');
        $days = AnalsisInfo::where('day', $date)->with('productanalysis')->get(['id','product_analysis_id','keyword','uv','pay_byr_cnt','pay_rate']);
        $days = $days->map(function($item){
            return [
               'skuid' => $item->productanalysis->skuid,
               'name'  => $item->productanalysis->name,
               'keyword'  => $item->keyword,
               'uv'  => $item->uv,
               'pay_byr_cnt'  => $item->pay_byr_cnt,
               'pay_rate'  => $item->pay_rate,
            ];
        });
        return $days->sortBy('skuid');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
