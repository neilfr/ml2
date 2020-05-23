<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Food extends Model
{
    protected $casts = [
        'user_id' => 'integer',
//        'kcal' => 'integer',
//        'fat' => 'integer',
//        'protein' => 'integer',
//        'carbohydrate' => 'integer',
//        'potassium' => 'integer',
//        'foodgroup_id' => 'integer',
    ];

    public function foodgroup()
    {
        return $this->belongsTo(Foodgroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
