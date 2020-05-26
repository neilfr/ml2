<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFoodRequest extends FormRequest
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
            'alias' => 'string',
            'description' => 'string',
            'kcal' => 'integer',
            'fat' => 'integer',
            'protein' => 'integer',
            'carbohydrate' => 'integer',
            'potassium' => 'integer',
            'favourite' => 'boolean',
            'source' => 'string',
            'foodgroup_id' => 'integer',
            'user_id' => 'integer',
        ];
    }
}
