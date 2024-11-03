<?php

namespace Saas\Project\Modules\User\UserByOwn\Edit\Requests;

use Saas\Project\Modules\User\Generics\Entities\User;

class Request
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
