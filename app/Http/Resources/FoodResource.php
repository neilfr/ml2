<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'alias' => $this->alias,
            'kcal' => $this->kcal,
            'fat' => $this->fat,
            'protein' => $this->protein,
            'carbohydrate' => $this->carbohydrate,
            'potassium' => $this->potassium,
            'favourite' => $this->favourite,
            'quantity' => $this->quantity,
            'foodgroup_id' => $this->foodgroup_id,
            'foodsource_id' => $this->foodsource_id,
            'user_id' => $this->user_id,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,

        ];
    }
}
