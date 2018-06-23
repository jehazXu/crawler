<?php

namespace App\Http\Controllers;

use App\Model\AnalsisInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Model\ProductAnalysis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ProductAnalysisController extends Controller
{
    const INFO_INDEX = [
        'product_analysis_id', 'keyword', 'uv', 'pv_value', 'pv_ratio',
        'bounce_self_uv', 'bounce_uv', 'clt_cnt', 'cart_byr_cnt', 'crt_byr_cnt',
        'crt_rate', 'pay_itm_cnt', 'pay_byr_cnt', 'pay_rate', 'created_at', 'updated_at'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $products = ProductAnalysis::all();
        $cookie = \App\Model\Cookie::first()->value ?? '';

        return view('analysis.show', compact('products', 'cookie'));
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $isexist = ProductAnalysis::where('skuid', $request->skuid)->first();
        if ($isexist) {
            return "exist";
        }
        try {
            $id = ProductAnalysis::create(['name' => $request->product, 'skuid' => $request->skuid, 'url' => $request->url])->id;
        } catch (Exception $e) {
            return $e->getMessage();
        }
        if ($id) {
            $cookie = $request->cookie;
            $message = $this->getMessage($cookie, $id, $request->skuid);
            if ($message === -1)
                return 'cookie';

            AnalsisInfo::insert($message);

            return 'success';
        } else {
            return 'fail';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $time = AnalsisInfo::where('product_analysis_id', $id)->max('created_at');
        return AnalsisInfo::where([['product_analysis_id', '=', $id], ['created_at', '=', $time],])->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //是否最新
        if (Carbon::parse(AnalsisInfo::where('product_analysis_id', $id)->max('created_at'))->isToday()) {
            return $this->show($id);
        }

        $product = ProductAnalysis::find($id);
        if (!$product) {
            return "exist";
        }


        $cookie = $request->cookie;
        $message = $this->getMessage($cookie, $id, $product->skuid);
        if ($message === -1)
            return 'cookie';

        AnalsisInfo::insert($message);

        return $message;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $pa = ProductAnalysis::find($id)->delete();
            $ai = AnalsisInfo::where('product_analysis_id', $id)->delete();
            if($pa&&$ai){
                DB::commit();
                return 'success';
            }
            DB::rollback();
            return 'fail';
        }catch (\Exception $exception){
            DB::rollback();
            return 'fail';
        }
    }


    /**
     * 获取接口内容
     * @param $cookie 淘宝登录cookie
     * @param $id product_analysis_id
     * @param $skuid 淘宝产品id
     * @return array|int
     */
    public function getMessage($cookie, $id, $skuid)
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
            $opt['url'] = "https://sycm.taobao.com/flow/new/item/source/detail.json?itemId={$skuid}&dateType=day&dateRange={$opt['date']}%7C{$opt['date']}&pageId=23.s1150&pPageId=23&pageLevel=2&childPageType=se_keyword&page={$page}&pageSize=20&order=desc&orderBy=uv&device=2&_=1529478971975&token=81deecc55";
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

            $collection2 = collect($message['data']['data'])->map(function($item) use ($id, $time) {
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
                return array_combine(self::INFO_INDEX, $res);
            })->toArray();
            $collection = array_merge($collection, $collection2);
        }
        curl_close($curl);
        return $collection;
    }
}
