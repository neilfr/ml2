<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FavouritefoodResource extends JsonResource
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
            'user_id' => auth()->user()->id,
            'code' => $this->code,
            'alias' => $this->alias,
            'description' => $this->description,
            'kcal' => $this->kcal,
            'potassium' => $this->potassium,
            'meals' => MealResource::collection($this->whenLoaded('meals')),
        ];
    }
}
