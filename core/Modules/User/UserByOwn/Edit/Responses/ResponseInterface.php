<?php

namespace Saas\Project\Modules\User\UserByOwn\Edit\Responses;

use Saas\Project\Modules\User\Generics\Entities\Status;
use Saas\Project\Modules\User\UserByOwn\Presenters\PresenterInterface;

interface ResponseInterface
{
    public function getPresenter(): PresenterInterface;

    public function getStatus(): Status;
}
