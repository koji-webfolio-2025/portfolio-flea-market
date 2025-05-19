<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'categories' => 'required|array|min:1',
            'condition' => 'required|string',
            'price' => 'required|integer|min:0',
            'brand_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'image_url.required' => '商品画像は必須です。',
            'title.required' => '商品名を入力してください。',
            'description.required' => '商品説明を入力してください。',
            'categories.required' => 'カテゴリーを選択してください。',
            'condition.required' => '商品の状態を選択してください。',
            'price.required' => '価格を入力してください。',
            'price.integer' => '価格は数値で入力してください。',
            'price.min' => '価格は0円以上で入力してください。',
            'brand_name.max' => 'ブランド名は255文字以内で入力してください。',
        ];
    }
}
