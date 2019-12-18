<?php

namespace App\Console\Commands;
use DB;
use Illuminate\Console\Command;
use App\Model\CollectCount;

class TmallCollectCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TmallCollectCount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'tmall collectcounts collect';

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
       $products= DB::table('tmall_products')->get();
       foreach ($products as $product) {
           $collectnum = CollectCount::collectCount($product->skuid);
            DB::table('collect_counts')->insert(
                ['tproduct_id' => $product->id, 'collect_count' => $collectnum,'count_date'=>date("Y-m-d H:i:s")]
            );
       }
    }
}
