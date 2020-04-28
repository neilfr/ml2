<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favouritefood extends Model
{
    protected $fillable = [
        'user_id',
        'code',
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
}
