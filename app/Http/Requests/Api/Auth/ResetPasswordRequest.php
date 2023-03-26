<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                'exists:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
            ],
            'token' => [
                'required',
                'string',
                'exists:password_resets,token',
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
            'password' => 'パスワード',
        ];
    }
}
