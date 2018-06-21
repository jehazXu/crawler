<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AnalsisInfo extends Model
{
    protected $table = 'analsis_infos';

    protected $fillable = [
        'product_analysis_id', 'keyword', 'uv', 'pv_value', 'pv_ratio',
        'bounce_self_uv', 'bounce_uv', 'clt_cnt', 'cart_byr_cnt', 'crt_byr_cnt',
        'crt_rate', 'pay_itm_cnt', 'pay_byr_cnt', 'pay_rate', 'm_ranking',
    ];
}
