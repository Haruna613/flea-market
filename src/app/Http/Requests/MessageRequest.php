<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
            'message_body' => 'required|max:400',
            'image_path'   => 'nullable|image|mimes:jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'message_body.required' => '本文を入力してください',
            'message_body.max'      => '本文は400文字以内で入力してください',
            'image_path.mimes'      => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }
}
