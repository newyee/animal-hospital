<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\MyPage;

use App\Http\Requests\FailedValidationTrait;
use App\Rules\PhoneNumberValidationRule;
use Illuminate\Foundation\Http\FormRequest;


class UserRequest extends FormRequest
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
            ],
            'phone_number' => [
                'nullable',
                new PhoneNumberValidationRule
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
            'name' => '氏名',
            'phone_number' => '電話番号',

        ];
    }
}
