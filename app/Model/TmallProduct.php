<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TmallProduct extends Model
{
    protected $table='tmall_products';

    public function collectCounts()
    {
        return $this->hasMany('App\Model\CollectCount','tproduct_id');
    }
}
