<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|max:2048',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'ユーザー名は必須です。',
            'profile_image.image' => '画像ファイルをアップロードしてください。',
            'profile_image.max' => '画像サイズは2MB以内にしてください。',
        ];
    }
}
