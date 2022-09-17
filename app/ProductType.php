<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $guarded = ['createdtime'];
    protected $table = 'producttype';
}
