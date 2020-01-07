<?php

namespace GreenBeans\Exceptions;

use Exception;
use Throwable;

class LexerException extends Exception
{

    public function __construct(string $message, array $token = [])
    {
        parent::__construct($message);
    }

}
