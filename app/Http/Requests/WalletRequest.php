<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class WalletRequest extends FormRequest
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

    public function rules()
    {
        return [
            "balance" => "required",
            "bank_name" => "required",
            "account_name" => "required",
            "account_number" => "required",
        ];
    }

    public function messages() {
        return [
            'balance.required' => 'Balance is required!',
            'bank_name.required' => 'Bank name is required!',
            'account_name.required' => 'Account name is required!',
            'account_number.required' => 'Account number is required!',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
