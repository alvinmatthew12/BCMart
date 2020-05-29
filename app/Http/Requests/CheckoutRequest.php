<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'stores' => 'array|required',
            'stores.*.id' => 'integer|required',
            'cart_items' => 'array|required',
            'cart_items.*.id' => [
                'required',
                Rule::exists('carts')->where(function($query){
                    $query->where('user_id', auth()->user()->id);
                }),
            ],
        ];
    }

    public function messages() {
        return [
            'stores.required' => 'Stores is required!',
            'cart_items.required' => 'Cart items is required!'
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
