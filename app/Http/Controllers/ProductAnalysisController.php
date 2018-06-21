<?php

namespace App\Http\Controllers;

use App\Model\AnalsisInfo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Model\ProductAnalysis;
use Illuminate\Support\Facades\Input;
use function Sodium\library_version_major;

class ProductAnalysisController extends Controller
{
    const INFO_INDEX = [
        'product_analysis_id', 'keyword', 'uv', 'pv_value', 'pv_ratio',
        'bounce_self_uv', 'bounce_uv', 'clt_cnt', 'cart_byr_cnt', 'crt_byr_cnt',
        'crt_rate', 'pay_itm_cnt', 'pay_byr_cnt', 'pay_rate'
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
        $cookie = Input::get('cookie');

        return $message = $this->getMessage($cookie, 10);

        \App\Model\AnalsisInfo::insert($message);

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

            \App\Model\AnalsisInfo::insert($message);

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
        return \App\Model\AnalsisInfo::where('product_analysis_id', $id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
        $opt['cookie'] = Input::get('cookie');
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

            $collection2 = collect($message['data']['data'])->map(function($item) use ($id) {
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
                return array_combine(self::INFO_INDEX, $res);
            })->toArray();
            $collection = array_merge($collection, $collection2);
        }
        curl_close($curl);
        return $collection;
    }
}
