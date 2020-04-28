<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favouritefoods()
    {
        return $this->belongsToMany(Favouritefood::class, 'favouritefood_meal');
    }
}
