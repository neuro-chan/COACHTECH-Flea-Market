<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // 2MB

            'building'    => 'nullable|string|max:200',
            'postal_code' => 'required|string|regex:/^\d{3}-\d{4}$/|max:8',
            'address'     => 'required|string|max:200',
        ];
    }

    public function messages(): array
    {
        return [
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex'    => '郵便番号は「123-4567」の形式で入力してください',
            'address.required'     => '住所を入力してください',
            'address.max'          => '住所は200文字以内で入力してください',
            'building.max'         => '建物名は200文字以内で入力してください',

            'profile_image.image' => '画像ファイルを選択してください',
            'profile_image.mimes' => '画像はjpeg/png/jpg/webp形式でアップロードしてください',
            'profile_image.max'   => '画像サイズは2MB以内にしてください',
        ];
    }
}
