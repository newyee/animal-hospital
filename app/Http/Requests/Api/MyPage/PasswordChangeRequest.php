<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\MyPage;

use App\Http\Requests\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class PasswordChangeRequest extends FormRequest
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
            //
            'current_password' => [
                'required',
                'string',
                'current_password:jwt',
            ],
            'new_password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'current_password' => '現在のパスワード',
            'new_password' => '新しいパスワード',
        ];
    }
}
