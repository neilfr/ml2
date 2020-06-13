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
        'foodgroup_id',
        'foodsource_id',
        'user_id',
    ];
    protected $casts = [
        'favourite' => 'boolean',
        'user_id' => 'integer',
        'foodsource_id' => 'integer',
        'foodgroup_id' => 'integer',
        'kcal' => 'integer',
        'fat' => 'integer',
        'protein' => 'integer',
        'carbohydrate' => 'integer',
        'potassium' => 'integer',
    ];

    public function foodgroup()
    {
        return $this->belongsTo(Foodgroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function foodsource()
    {
        return $this->belongsTo(Foodsource::class);
    }

    public function parentfoods()
    {
        return $this->belongsToMany(Food::class, 'food_food', 'child_food_id', 'parent_food_id');
    }

    public function childfoods()
    {
        return $this->belongsToMany(Food::class, 'food_food', 'parent_food_id', 'child_food_id');
    }
}
