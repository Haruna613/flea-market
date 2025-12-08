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
            'item_name' => ['required', 'string', 'max:255'],
            'item_description' => ['required', 'string', 'max:255'],
            'item_image' => ['required', 'image', 'mimes:jpeg,png'],
            'item_category' => ['required', 'array'],
            'item_category.*' => ['exists:categories,id'],
            'item_condition' => ['required', 'exists:conditions,id'],
            'item_price' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'item_name.required' => '商品名は必ず入力してください。',
            'item_description.required' => '商品説明は必ず入力してください。',
            'item_description.max' => '商品説明は255文字以内で入力してください。',
            'item_image.required' => '商品画像をアップロードしてください。',
            'item_image.image' => '有効な画像ファイルをアップロードしてください。',
            'item_image.mimes' => '画像の拡張子は.jpegまたは.pngにしてください。',
            'item_category.required' => 'カテゴリーを1つ以上選択してください。',
            'item_condition.required' => '商品の状態を選択してください。',
            'item_price.required' => '販売価格は必ず入力してください。',
            'item_price.integer' => '販売価格は数値で入力してください。',
            'item_price.min' => '販売価格は0円以上で入力してください。',
        ];
    }
}
