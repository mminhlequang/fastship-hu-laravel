<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GreaterThanFieldRule implements Rule
{
    protected $otherField;

    public function __construct($otherField)
    {
        $this->otherField = $otherField;
    }

    public function passes($attribute, $value)
    {
        $otherFieldValue = request()->input($this->otherField);

        return $value > $otherFieldValue;
    }

    public function message()
    {
        return 'Tuổi sau phải lớn hơn tuổi trước';
    }
}
