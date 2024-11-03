<?php

namespace Saas\Project\Modules\User\UserByOwn\Edit\Rules;

use Saas\Project\Modules\User\Exceptions\UserEditException;
use Saas\Project\Modules\User\Generics\Entities\User;
use Saas\Project\Modules\User\Generics\Gateways\UserGateway;

class UserEditRule
{
    private User $user;

    private UserGateway $userGateway;

    public function __construct(
        UserGateway $userGateway,
        User $user
    ) {
        $this->userGateway = $userGateway;
        $this->user = $user;
    }

    public function apply(): User
    {
        try {
            return $this->userGateway->update($this->user);
        } catch (\Throwable $ex) {
            throw new UserEditException('Error update user', 500, $ex);
        }
    }
}
