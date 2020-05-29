<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "name" => "required",
            "price" => "required",
            "store_id" => "required",
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Name is required!',
            'price.required' => 'Price is required!',
            'store_id.required' => 'Store id is required!'
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
