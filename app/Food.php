<?php

namespace App;

use App\Foodsource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


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
        'base_quantity',
        'editable',
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
        'base_quantity' => 'integer',
        'editable' => 'boolean',
    ];

    public function scopeUserFoods(Builder $query)
    {
        $query->where('user_id', auth()->user()->id);
    }

    public function scopeSharedFoods(Builder $query)
    {
        $sharableFoodsourceIds = Foodsource::sharable()->get()->pluck('id');
        $query->orWhere('foodsource_id', '=', $sharableFoodsourceIds);
    }

    public function scopeAliasSearch(Builder $query, ?string $aliasSearch = null)
    {
        if (is_null($aliasSearch)) {
            return $query;
        }

        $query->where('alias', 'like', "%{$aliasSearch}%");
    }

    public function scopeDescriptionSearch(Builder $query, ?string $descriptionSearch=null)
    {
        if (is_null($descriptionSearch)) {
            return $query;
        }

        $query->where('description', 'like', "%{$descriptionSearch}%");
    }

    public function scopeFoodgroupSearch(Builder $query, $foodgroupSearch)
    {
        if (is_null($foodgroupSearch)) {
            return $query;
        }

        $query->where('foodgroup_id', '=', "{$foodgroupSearch}");
    }

    public function scopeFavouritesFilter(Builder $query, $favouritesFilter)
    {
        if (is_null($favouritesFilter) || $favouritesFilter==="no") {
            return $query;
        }
        if ($favouritesFilter==="yes") {
            $favouriteIds = User::find(auth()
                ->user()->id)
                ->favourites()->pluck('food_id');
            $query->whereIn('id', $favouriteIds);
        }
    }

    public function foodgroup()
    {
        return $this->belongsTo(Foodgroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function foodsource()
    {
        return $this->belongsTo(Foodsource::class);
    }

    public function parentfoods()
    {
        return $this->belongsToMany(Food::class, 'ingredients', 'ingredient_id', 'parent_food_id');
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'ingredients', 'parent_food_id', 'ingredient_id')
            // ->as('ingredients')
            ->withPivot('id', 'quantity');
    }
}
