<?php

namespace App\Exceptions;

use Exception;

class DataErrorException extends Exception
{
    /**
     * @var object $errorExceptions
     */
    private $errorExceptions;
    public $code = 403;

    public function __construct($errorExceptions)
    {
        $this->errorExceptions = $errorExceptions;
    }

    public function render()
    {
        return response()->json($this->errorExceptions, $this->code);
    }
}
