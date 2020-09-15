<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'alias' => [
              'string',
              'nullable',
                Rule::unique('foods', 'alias')->where(function ($query) {
                    return $query->where('user_id', Auth::User()->id);
                }),
            ],
            'description' => [
                'string',
                Rule::unique('foods', 'description')->where(function ($query){
                    return $query->where('user_id', Auth::User()->id);
                }),
            ],
            'kcal' => 'integer|min:0',
            'fat' => 'integer|min:0',
            'protein' => 'integer|min:0',
            'carbohydrate' => 'integer|min:0',
            'potassium' => 'integer|min:0',
            'quantity' => 'integer|min:0',
            'favourite' => 'boolean',
            'foodgroup_id' => 'exists:App\Foodgroup,id',
            'foodsource_id' => 'exists:App\Foodsource,id',
            'user_id' => 'exists:App\User,id',
        ];
    }
}
