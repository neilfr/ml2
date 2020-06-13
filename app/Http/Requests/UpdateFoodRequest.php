<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFoodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'alias' => 'string|nullable',
            'description' => 'string',
            'kcal' => 'integer',
            'fat' => 'integer',
            'protein' => 'integer',
            'carbohydrate' => 'integer',
            'potassium' => 'integer',
            'favourite' => 'boolean',
            'foodgroup_id' => 'exists:App\Foodgroup,id',
            'foodsource_id' => 'exists:App\Foodsource,id',
            'user_id' => 'exists:App\User,id',
        ];
    }
}
