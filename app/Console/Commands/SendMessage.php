<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class SendMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->getMessage();
    }

    public function getMessage()
    {
        $skuid = \App\Model\ProductAnalysis::all()->first()->skuid;

        $opt['cookie'] = \App\Model\Cookie::first();
        //是否存在cookie
        if (!$opt['cookie']) return 'cookieisnull';

        $opt['date'] = Carbon::yesterday()->toDateString();
        $opt['header'][] = 'User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36';
        $opt['header'][] = 'Cookie:cookie2=' . $opt['cookie']->value;
        $opt['url'] = "https://sycm.taobao.com/flow/new/item/source/detail.json?itemId={$skuid}&dateType=day&dateRange={$opt['date']}%7C{$opt['date']}&pageId=23.s1150&pPageId=23&pageLevel=2&childPageType=se_keyword&page=1&pageSize=1&order=desc&orderBy=uv&device=2&_=1529478971975&token=81deecc55";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $opt['header']);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_URL, $opt['url']);
        $str = curl_exec($curl);
        $message = json_decode($str, true);
        // dd($opt);

        if ($message['code'] !== 0) {
            $opt['cookie']->delete();
        }

        curl_close($curl);

        file_put_contents(public_path('log.log'),Carbon::now(),FILE_APPEND|LOCK_EX);
    }

}
