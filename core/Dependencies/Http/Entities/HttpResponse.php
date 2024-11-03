<?php

namespace Saas\Project\Dependencies\Http\Entities;

use Saas\Project\Dependencies\Http\Collections\HeaderCollection;

class HttpResponse
{
    private int $statusCode;
    private string $body;
    private HeaderCollection $headers;

    public function __construct(int $code, string $body, HeaderCollection $headers)
    {
        $this->statusCode = $code;
        $this->body = $body;
        $this->headers = $headers;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getHeaderCollection(): HeaderCollection
    {
        return $this->headers;
    }
}
