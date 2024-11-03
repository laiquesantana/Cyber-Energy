<?php

namespace Saas\Project\Modules\User\UserByOwn\Creation\Responses;

use Saas\Project\Modules\User\Generics\Entities\Status;
use Saas\Project\Modules\User\UserByOwn\Presenters\PresenterInterface;

interface ResponseInterface
{
    public function getStatus(): Status;
}
