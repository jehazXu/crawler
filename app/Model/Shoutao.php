<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shoutao extends Model
{
    //
    public function rankings()
    {
        return $this->hasMany('App\Model\Shoutaoranking','shoutao_id');
    }
}
