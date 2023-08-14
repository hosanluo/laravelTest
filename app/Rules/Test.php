<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Test implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->isValid($value)) {
            $fail('The :attribute value is wrong.');
        }
    }

    /**
     * @param $s
     * @return bool
     */
    private function isValid($s): bool
    {
        $dictionary = [
            ')' => '(',
            '}' => '{',
            ']' => '['
        ];
        $stack = [];
        $arr = str_split($s);
        foreach ($arr as $item) {
            if(!empty($dictionary[$item])) {
                if(!empty($stack[0]) && $stack[0] == $dictionary[$item]) {
                    array_shift($stack);
                } else {
                    return false;
                }
            } else {
                array_unshift($stack,$item);
            }
        }
        if(empty($stack)) {
            return true;
        } else {
            return false;
        }
    }
}
