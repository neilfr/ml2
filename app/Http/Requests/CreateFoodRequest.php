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
                'required',
                'string',
                Rule::unique('foods', 'description')->where(function ($query){
                    return $query->where('user_id', Auth::User()->id);
                }),
            ],
            'kcal' => 'required|integer|min:0',
            'fat' => 'required|integer|min:0',
            'protein' => 'required|integer|min:0',
            'carbohydrate' => 'required|integer|min:0',
            'potassium' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:0',
            'favourite' => 'required|boolean',
            'foodgroup_id' => 'required|exists:App\Foodgroup,id',
            'foodsource_id' => 'required|exists:App\Foodsource,id',
            'user_id' => 'required|exists:App\User,id',
        ];
    }
}
