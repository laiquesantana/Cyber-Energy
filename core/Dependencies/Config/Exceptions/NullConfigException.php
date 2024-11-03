<?php

namespace Saas\Project\Dependencies\Config\Exceptions;

use Exception;

class NullConfigException extends Exception
{
    public function __construct(
        string $message,
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}