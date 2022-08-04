<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportValidationRequest extends FormRequest
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
            'rate' => 'required',
            'body' => 'required|max:150',
        ];
    }

    /**
     * 定義済みバリデーションルールのエラーメッセージ取得
     *
     * @return array
     */
    public function messages()
    {
        return [
            "rate.required" => "自己評価は必須入力です。",
            "body.required" => "内容は必須入力です。",
            "body.max" => "150文字以内でご入力ください。",
        ];
    }
}
