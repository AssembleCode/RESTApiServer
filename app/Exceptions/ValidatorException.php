<?php

namespace App\Exceptions;

use Exception;

class ValidatorException extends Exception
{
    /**
     * @var object $exception
     */
    private $exception;
    public $code = 422;

    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    public function render()
    {
        return response()->json($this->exception->errors(), $this->code);
    }
}
