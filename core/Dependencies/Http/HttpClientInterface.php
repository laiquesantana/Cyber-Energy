<?php

namespace Saas\Project\Dependencies\Http;

use Saas\Project\Dependencies\Http\Entities\HttpResponse;

interface HttpClientInterface
{
    public function post(
        string $endpoint,
        $data,
        array $headers = [],
        string $contentType = 'application/json'
    ): HttpResponse;

    public function get(string $endpoint, array $headers = [], bool $httpError = true): string;
}
