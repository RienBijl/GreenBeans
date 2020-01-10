<?php

namespace GreenBeans\Exceptions;

use Exception;

class EncryptionException extends Exception
{

    public function __construct(string $message)
    {
        parent::__construct($message);
    }

}
