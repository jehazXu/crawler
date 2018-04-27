<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CollectCount extends Model
{
    protected $table = 'collect_counts';

    public function product()
    {
        return $this->belongsTo('App\Model\TmallProduct','tproduct_id');
    }
}
