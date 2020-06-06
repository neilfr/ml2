<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Foodsource extends Model
{
    protected $casts = [
        'sharable' => 'boolean',
    ];
}
