<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'description' => ['required', 'max:255'],
            'item_image' => ['required', 'mimes:jpeg,png'],
            'category_ids' => ['required'],
            'condition_id' => ['required'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください',
            'item_image.required' => '商品画像を選択してください',
            'item_image.mimes' => '商品画像は.jpegまたは.png形式でアップロードしてください',
            'category_ids.required' => '商品のカテゴリーを選択してください',
            'condition_id.required' => '商品の状態を選択してください',
            'price.required' => '商品価格を入力してください',
            'price.numeric' => '商品価格は数値で入力してください',
            'price.min' => '商品価格は0円以上で入力してください',
        ];
    }
}
