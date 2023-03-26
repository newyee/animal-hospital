<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'currentPassword' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (! Auth::validate(['email' => Auth::user()->email, 'password' => $value])) {
                        return $fail(__('現在のパスワードが一致しません。'));
                    }
                },
            ],
            'newPassword' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
            ],
        ];
    }
}
