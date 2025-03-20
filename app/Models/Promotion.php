<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'promotions';

    protected $fillable = [
        'code_name',
        'discount',
        'expiration_date',
        'status',
    ];

    protected $dates = ['expiration_date'];
}
