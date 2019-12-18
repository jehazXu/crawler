<?php

namespace App\Http\Controllers;

use App\Model\AnalsisInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Model\ProductAnalysis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductAnalysisController extends Controller
{
    const INFO_INDEX = [
        'product_analysis_id', 'keyword', 'uv', 'pv_value', 'pv_ratio',
        'bounce_self_uv', 'bounce_uv', 'clt_cnt', 'cart_byr_cnt', 'crt_byr_cnt',
        'crt_rate', 'pay_itm_cnt', 'pay_byr_cnt', 'pay_rate', 'created_at', 'updated_at', 'day'
    ];

    /**
     * 显示产品分析页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //分析页
        $products = ProductAnalysis::orderBy('id','desc')->paginate(6);;
        $cookie = \App\Model\Cookie::first()->value ?? '';

        //汇总页
        $list = AnalsisInfo::groupBy('day')->orderBy('day','desc')->pluck('day');
        $date = AnalsisInfo::where('day','2018-06-28')->with('productanalysis')->get(['id','product_analysis_id','keyword','uv','pay_byr_cnt','pay_rate']);
        $dates = $date->map(function($item){
            return [
               'skuid' => $item->productanalysis->skuid,
               'name'  => $item->productanalysis->name,
               'keyword'  => $item->keyword,
               'uv'  => $item->uv,
               'pay_byr_cnt'  => $item->pay_byr_cnt,
               'pay_rate'  => $item->pay_rate,
            ];
        });

        $dates = $dates->sortBy('skuid');

        return view('analysis.show', compact('products', 'cookie', 'list', 'dates'));
    }


    /**
     * 产品分析入库
     * @param Request $request
     * @return string
     */
    public function store(Request $request)
    {
        $cookie = $skuid = $product = $url = $keyword = $str_time = $end_time = '';

        $validator = Validator::make($request->all(), [
            'cookie' => 'required|string',
            'skuid' => 'required|string',
            'product' => 'required|string',
            'url' => 'required|string',
            'keyword' => 'required|string',
            'str_time' => 'required|date',
            'end_time' => 'required|date',
        ]);

        if ($validator->fails()) {
            return 'key';
        }

        extract($request->input());

        try {
            $id = ProductAnalysis::create([
                'name' => $product, 'skuid' => $skuid, 'url' => $url, 'keyword' => $keyword,
                'str_time' => $str_time, 'end_time' => $end_time
            ])->id;
        } catch (Exception $e) {
            return $e->getMessage();
        }
        if ($id) {

            $str_day = Carbon::parse($str_time);
            $dayNum = $str_day->diffInDays($end_time, false) + 1;

            //天数必须大于0
            if ($dayNum <= 0) return 'day';

            $message = [];
            //获取每一天的数据
            for ($i = 0; $i < $dayNum; $i++) {
                $day = $str_day->addDay($i ? 1 : 0)->toDateString();
                $data = $this->getMessage($cookie, $id, $skuid, $day, $day);

                if ($data === -1) return 'cookie';
                $data = $this->matchKey($data, $keyword);

                if (!$data) return 'null';
                $message[] = $data;
            }

            AnalsisInfo::insert($message);

            return 'success';
        }

        return 'fail';

    }

    /**
     * 获取产品的分析数据
     * @param $id 产品id
     * @return mixed
     */
    public function show($id)
    {
        $dates = ProductAnalysis::findOrFail($id,['str_time', 'end_time']);
        return AnalsisInfo::where('product_analysis_id', $id)->whereBetween('day', [$dates->str_time, $dates->end_time])->orderBy('day')->get();
    }

    /**
     * 更新产品分析数据
     * @param Request $request
     * @param $id 产品id
     * @return array|int|mixed|string
     */
    public function update(Request $request, $id)
    {
       
        $cookie = $str_time = $end_time = '';

        $validator = Validator::make($request->all(), [
            'cookie' => 'required|string',
            'str_time' => 'required|date',
            'end_time' => 'required|date'
        ]);

        if ($validator->fails()) {
            return 'required';
        }

        extract($request->input());

        $product = ProductAnalysis::findOrFail($id);

        $days = $this->getDate($id, $str_time, $end_time);

        if ($days === 'day') return 'day';

        $message = [];
        foreach($days as $day){
            $data = $this->getMessage($cookie, $id, $product->skuid, $day, $day);

            if ($data === -1) return 'cookie';

            $data = $this->matchKey($data, $product->keyword);

            if (!$data) return 'null';

            $message[] = $data;
        }

        DB::beginTransaction();
        try {

            if (AnalsisInfo::insert($message) !== false && $product->update(['str_time' => $str_time, 'end_time' => $end_time]) !== false) {

                DB::commit();
                return $this->show($id);
            }
            DB::rollback();
            return 'fail';
        } catch (\Exception $exception) {
            DB::rollback();
            return 'fail';
        }

    }

    /**
     * 计算要查询的日期
     *
     * @param [type] $id  产品id
     * @param [type] $str_time 开始时间
     * @param [type] $end_time 结束时间
     * @return array | string
     */
    public function getDate($id, $str_time,$end_time){
        $str_day = Carbon::parse($str_time);
        //获得日期间隔的天数
        $dayNum = $str_day->diffInDays($end_time, false) + 1;

        //天数必须大于0
        if ($dayNum <= 0) return 'day';

        for ($i = 0; $i < $dayNum; $i++) {
            $day[] = $str_day->addDay($i ? 1 : 0)->toDateString();
        }

        //计算数据没有的日期
        return collect($day)->diff(AnalsisInfo::where('product_analysis_id',$id)->pluck('day'));
    }

    /**
     * 删除产品数据
     * @param $id 产品id
     * @return string
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pa = ProductAnalysis::find($id)->delete();
            $ai = AnalsisInfo::where('product_analysis_id', $id)->delete();
            if ($pa !== false && $ai !== false) {
                DB::commit();
                return 'success';
            }
            DB::rollback();
            return 'fail';
        } catch (\Exception $exception) {
            DB::rollback();
            return 'fail';
        }
    }


    /**
     * 获取接口内容
     * 
     * @param $cookie 淘宝登录cookie
     * @param $id product_analysis_id 产品id
     * @param $skuid 淘宝产品id
     * @param [string] $str_time 开始日期
     * @param [string] $end_time 结束日期
     * @return sting | int
     */
    public function getMessage($cookie, $id, $skuid, $str_time, $end_time)
    {
        $time = Carbon::now()->toDateString();
        $opt['cookie'] = $cookie;
        $opt['date'] = Carbon::yesterday()->toDateString();
        $opt['header'][] = 'User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36';
        $opt['header'][] = 'Cookie:cookie2=' . $opt['cookie'];
        $cookie = \App\Model\Cookie::whereValue($opt['cookie']);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $opt['header']);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $page = 0;
        $collection = [];
        while (true) {
            $page++;
            $opt['url'] = "https://sycm.taobao.com/flow/new/item/source/detail.json?itemId={$skuid}&dateType=day&dateRange={$str_time}%7C{$end_time}&pageId=23.s1150&pPageId=23&pageLevel=2&childPageType=se_keyword&page={$page}&pageSize=20&order=desc&orderBy=uv&device=2&_=1529478971975&token=81deecc55";
            curl_setopt($curl, CURLOPT_URL, $opt['url']);
            $str = curl_exec($curl);

            $message = json_decode($str, true);

            if ($message['code'] !== 0) {
                if ($cookie->count())
                    $cookie->delete();
                return -1; //cookie失效
            }

            if (!$cookie->count()) \App\Model\Cookie::create(['value' => $opt['cookie']]);

            if (count($message['data']['data']) === 0) break;

            $collection2 = collect($message['data']['data'])->map(function ($item) use ($id, $time, $str_time) {
                $res[] = $id;
                $res[] = $item['pageName']['value'];
                $res[] = $item['uv']['value'];
                $res[] = $item['pv']['value'];
                $res[] = round($item['pv']['ratio'] * 100, 2);
                $res[] = $item['bounceSelfUv']['value'];
                $res[] = $item['bounceUv']['value'];
                $res[] = $item['cltCnt']['value'];
                $res[] = $item['cartByrCnt']['value'];
                $res[] = $item['crtByrCnt']['value'];
                $res[] = round($item['crtRate']['value'] * 100, 2);
                $res[] = $item['payItmCnt']['value'];
                $res[] = $item['payByrCnt']['value'];
                $res[] = round($item['payRate']['value'] * 100, 2);
                $res[] = $time;
                $res[] = $time;
                $res[] = $str_time;
                return array_combine(self::INFO_INDEX, $res);
            })->toArray();
            $collection = array_merge($collection, $collection2);
        }
        curl_close($curl);
        return $collection;
    }

    /**
     * 匹配关键字
     *
     * @param [array] $data    匹配的数据
     * @param [string] $keyword 关键字
     * @return array | null
     */
    public function matchKey($data, $keyword)
    {
        return collect($data)->firstWhere('keyword', $keyword);
    }

}
