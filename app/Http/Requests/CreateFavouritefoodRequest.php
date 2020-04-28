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
            'code' => 'string|nullable',
            'user_id' => 'integer|nullable',
            'alias' => 'string|nullable',
            'description' => 'required',
            'kcal' => 'integer',
            'potassium' => 'integer',
        ];
    }
}
