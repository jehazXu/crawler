<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tmallproduct extends Model
{
    protected $table='tmall_products';

    public function collectCounts()
    {
        return $this->hasMany('App\Model\Collectcount','tproduct_id');
    }
}
