<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Auth;

use \App\Rules\AlphaRule;
use App\Http\Requests\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

final class RegisterRequest extends FormRequest
{
    use FailedValidationTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
            ],
            'email' => [
                'required',
                'string',
                'email',
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',
                'max:50',
                new AlphaRule,
            ],
            'password_confirmation' => [
                'required',
                'string',
                'max:50',
            ],
        ];
    }

    /**
     *  バリデーション項目名定義
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => '名前',
            'email' => 'メールアドレス',
            'password' => 'パスワード',
            'password_confirmation' => 'パスワード(確認入力)'
        ];
    }
}
