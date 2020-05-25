<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Food extends Model
{
    protected $fillable = [
        'alias',
        'description',
        'kcal',
        'fat',
        'protein',
        'carbohydrate',
        'potassium',
        'favourite',
        'source',
        'foodgroup_id',
        'user_id',
    ];
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
