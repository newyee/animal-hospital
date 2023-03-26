<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\MyPage;

use App\Http\Requests\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;


class EmailChangeRequest extends FormRequest
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
                'string',
                'email',
            ],
            'user_id' => [
                'required',
                'numeric',
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
            'user_id' => 'ID',
            'email' => 'メールアドレス',

        ];
    }
}
