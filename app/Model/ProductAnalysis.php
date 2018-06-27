<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAnalysis extends Model
{
    use SoftDeletes;

    protected $table = 'product_analysis';

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'skuid', 'url', 'keyword', 'str_time', 'end_time'];

    protected $hidden = ['deleted_at'];

    public function analsisinfos (){
        return $this->hasMany(\App\Model\AnalsisInfo::class);
    }

}
