<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userId = $this->user()->id;

        return [
            'username' => 'required|string|max:20',
            'postal_code' => 'required|string|regex:/^[0-9]{3}-[0-9]{4}$/',
            'address' => 'required|string|max:255',
            'building_name' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        $userId = $this->user()->id;

        return [
            'username.required' => 'お名前を入力してください',
            'username.max' => 'お名前は20文字以内で入力してください。',

            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号は「123-4567」の形式で入力してください',

            'address.required' => '住所を入力してください',

            'profile_image.mimes' => 'プロフィール画像の拡張子は.jpegまたは.pngである必要があります',
        ];
    }
}
