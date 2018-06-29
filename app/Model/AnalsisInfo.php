<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnalsisInfo extends Model
{
    use SoftDeletes;

    protected $table = 'analsis_infos';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'product_analysis_id', 'keyword', 'uv', 'pv_value', 'pv_ratio',
        'bounce_self_uv', 'bounce_uv', 'clt_cnt', 'cart_byr_cnt', 'crt_byr_cnt',
        'crt_rate', 'pay_itm_cnt', 'pay_byr_cnt', 'pay_rate', 'm_ranking', 'day'
    ];

    protected $hidden = ['deleted_at'];

    public function productanalysis(){
        return $this->belongsTo(\App\Model\ProductAnalysis::class,'product_analysis_id');
    }

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toDateString();
    }
    public function getUpdatedAtAttribute($value){
        return Carbon::parse($value)->toDateString();
    }
 

}
