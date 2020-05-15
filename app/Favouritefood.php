<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favouritefood extends Model
{
    protected $casts = [
        'user_id' => 'integer',
        'kcal' => 'integer',
        'potassium' => 'integer',
    ];

    protected $fillable = [
        'user_id',
        'alias',
        'description',
        'kcal',
        'potassium',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meals()
    {
        return $this->belongsToMany(Meal::class);
    }

    public function foodgroup()
    {
        return $this->belongsTo(Foodgroup::class);
    }
}
