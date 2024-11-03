<?php

declare(strict_types=1);

namespace Saas\Project\Packages\OpenAi\Dependencies;

use Saas\Project\Dependencies\Http\HttpClientInterface;
use Saas\Project\Modules\Generics\Entities\Status;

interface ResponseInterface
{
    public function getStatus(): Status;

    public function getHttpClientInterface(): HttpClientInterface;
}
