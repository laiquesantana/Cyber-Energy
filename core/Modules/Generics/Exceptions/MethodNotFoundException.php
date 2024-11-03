<?php

declare(strict_types=1);

namespace Saas\Project\Modules\Generics\Exceptions;

use Exception;

class MethodNotFoundException extends Exception
{
    private string $methodName;

    public function __construct(string $methodName)
    {
        $this->methodName = $methodName;

        parent::__construct("Trying to call nonexistent method");
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }
}
