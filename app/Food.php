<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    public function foodgroup()
    {
        return $this->belongsTo(Foodgroup::class);
    }
}
