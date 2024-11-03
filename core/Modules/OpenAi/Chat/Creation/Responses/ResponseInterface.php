<?php

namespace Saas\Project\Modules\OpenAi\Chat\Creation\Responses;

use Saas\Project\Modules\User\Generics\Entities\Status;
use Saas\Project\Modules\User\UserByOwn\Presenters\PresenterInterface;

interface ResponseInterface
{
    public function getStatus(): Status;
}
