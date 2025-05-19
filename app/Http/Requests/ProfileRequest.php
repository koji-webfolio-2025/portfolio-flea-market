<?php

namespace App\Http\Requests;

use App\Http\Requests\AddressRequest;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        $addressRules = (new AddressRequest())->rules();

        return array_merge($addressRules, [
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|mimes:jpeg,png',
        ]);

    }

    public function messages()
    {
        $addressMessages = (new AddressRequest())->messages();

        return array_merge($addressMessages, [
            'name.required' => 'ユーザー名を入力してください。',
            'name.max' => 'ユーザー名は255文字以内で入力してください。',
            'profile_image.mimes' => 'プロフィール画像はjpegまたはpng形式でアップロードしてください。',
        ]);

    }
}
