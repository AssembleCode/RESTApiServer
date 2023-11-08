<?php

namespace App\Services;


class PasswordPatternService
{
    private $pass_length = 6;
    private $pass_is_uppercase;
    private $pass_is_lowercase;
    private $pass_is_number;
    private $pass_is_special_char;

    public function __construct($pass_length, $pass_is_uppercase = false, $pass_is_lowercase = false, $pass_is_number = false, $pass_is_special_char = false)
    {
        $this->pass_length = $pass_length;
        $this->pass_is_uppercase = $pass_is_uppercase;
        $this->pass_is_lowercase = $pass_is_lowercase;
        $this->pass_is_number = $pass_is_number;
        $this->pass_is_special_char = $pass_is_special_char;
    }

    public function policyMaker()
    {
        $passLength = $this->pass_length <= 0 ? 6 : $this->pass_length;

        if ($passLength) {
            $lengthRegx = '(?=^.{' . $passLength . ',}$)';
            $_pattern = $lengthRegx;
            $_message = "Minimum length should be $passLength characters";
        }

        $_message .= " and there should be a combination of ";

        if ($this->pass_is_uppercase) {
            $uppercaseRegx = '(?=.*[A-Z])';
            $_pattern .= $uppercaseRegx;
            $_message .= "uppercase (A-Z), ";
        }

        if ($this->pass_is_lowercase) {
            $lowercaseRegx = '(?=.*[a-z])';
            $_pattern .= $lowercaseRegx;
            $_message .= "lowercase (a-z), ";
        }

        if ($this->pass_is_number) {
            $numberRegx = '(?=.*[0-9])';
            $_pattern .= $numberRegx;
            $_message .= "number (0-9), ";
        }

        if ($this->pass_is_special_char) {
            $specialCharRegx = '(?=.*[@$!%*?&])';
            $_pattern .= $specialCharRegx;
            $_message .= "special character (e.g. *#@$%&!), ";
        }

        $_pattern .= '.*$';
        $_message = rtrim($_message, ', ');
        $_message .= '';

        return [
            'pattern' => $_pattern,
            'patterns' => [
                'length' => $passLength ?? null,
                'uppercase' => $uppercaseRegx ?? null,
                'lowercase' => $lowercaseRegx ?? null,
                'number' => $numberRegx ?? null,
                'special_character' => $specialCharRegx ?? null,
                'message' => $_message ?? null,
            ]
        ];
    }
}
