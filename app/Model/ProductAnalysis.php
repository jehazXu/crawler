<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProductAnalysis extends Model
{
    protected $table = 'product_analysis';

    protected $fillable = ['name', 'skuid', 'url'];

}
