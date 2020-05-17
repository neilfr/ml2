<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Foodgroup extends Model
{
    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    public function favouritefoods()
    {
        return $this->hasMany(Favouritefoods::class);
    }
}
