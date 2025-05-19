<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
            'postal_code' => ['required', 'regex:/^\d{3}-?\d{4}$/'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
        ];
    }
    public function messages()
    {
        return [
            'postal_code.required' => '郵便番号は必須です',
            'postal_code.regex' => '郵便番号は「123-4567」形式で入力してください',
            'address_line1.required' => '住所は必須です',
        ];
    }
}
