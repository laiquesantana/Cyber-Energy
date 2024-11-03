<?php

declare(strict_types=1);

namespace Saas\Project\Packages\OpenAi\Chat\Responses;

use Saas\Project\Dependencies\Http\HttpClientInterface;
use Saas\Project\Modules\Generics\Entities\Status;
use Saas\Project\Packages\OpenAi\Dependencies\ResponseInterface;

class Response implements ResponseInterface
{
    private Status $status;
    private HttpClientInterface $httpClientInterface;

    public function __construct(
        Status              $status,
        HttpClientInterface $httpClientInterface

    )
    {
        $this->httpClientInterface = $httpClientInterface;

        $this->status = $status;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getHttpClientInterface(): HttpClientInterface
    {
        return $this->httpClientInterface;
    }
}
