<?php

namespace Saas\Project\Dependencies\Http\Exceptions;

use Exception;
use Throwable;

class RequestFailureException extends Exception
{
    private string $endpoint;

    public function __construct(
        string $message,
        Throwable $previous = null,
        int $code = 0
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): RequestFailureException
    {
        $this->endpoint = $endpoint;
        return $this;
    }
}
