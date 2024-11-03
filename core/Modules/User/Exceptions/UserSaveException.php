<?php

namespace Saas\Project\Modules\User\Exceptions;

use Exception;
use Throwable;

class UserSaveException extends Exception
{
    public function __construct(
        string $message,
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
