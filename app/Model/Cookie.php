<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cookie extends Model
{
    use SoftDeletes;

    protected $table = 'cookies';

    protected $fillable = ['value'];

    protected $dates = ['deleted_at'];

}
