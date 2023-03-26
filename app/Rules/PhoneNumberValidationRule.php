<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;


class PhoneNumberValidationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^(0\d{1,4})[-]?([0-9]\d*)[-]?([0-9]\d*)$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '電話番号に正しい形式を指定してください。';
    }
}
