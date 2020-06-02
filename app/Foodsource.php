<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Foodsource extends Model
{
    protected $casts = [
        'updatable' => 'boolean',
        'deletable' => 'boolean',
    ];
}
