<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeclarationValidationRequest extends FormRequest
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
            'title' => 'required|max:15',
            'start_date' =>  'required|date|after:yesterday',
            'end_date' => 'required|date|after:start_date',
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
            "title.required" => "タイトルは必須入力です。",
            "start_date.required" => "開始日は必須入力です。",
            "end_date.required" => "終了日は必須入力です。",
            "body.required" => "内容は必須入力です。",
            "title.max" => "15文字以内でご入力ください。",
            "body.max" => "150文字以内でご入力ください。",
            "start_date.after" => "開始日は今日以降の日付を指定してください。",
            "end_date.after" => "終了日は開始日以降の日付を指定してください。"
        ];
    }
}
