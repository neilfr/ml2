<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFavouritefoodRequest extends FormRequest
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
//            'code' => 'string|nullable',
            'user_id' => 'integer',
            'alias' => 'string|nullable',
            'description' => 'required',
            'kcal' => 'integer|min:0',
            'potassium' => 'integer|min:0',
        ];
    }
}
