<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WithdrawalRequest extends FormRequest
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
            'CurrentPassword' => ['required', 'string', 'min:8'],
        ];
    }

    public function withValidator(Validator $validator) {
        $validator->after(function ($validator) {
            $auth = Auth::user();
            //現在のパスワードと新しいパスワードが合わなければエラーを出力
            if (!(Hash::check($this->input('CurrentPassword'), $auth->password))) {
                $validator->errors()->add('CurrentPassword', __('Password does not match'));
            }
        });
    }
}
