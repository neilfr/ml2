<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Foodsource extends Model
{
    protected $casts = [
        'sharable' => 'boolean',
    ];

    public function scopeSharable(Builder $query)
    {
        return $query->where('sharable', '=', true);
    }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }
}
