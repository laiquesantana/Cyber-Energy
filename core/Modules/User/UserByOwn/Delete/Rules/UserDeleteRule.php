<?php

namespace Saas\Project\Modules\User\UserByOwn\Delete\Rules;

use Saas\Project\Modules\User\Exceptions\UserDeleteException;
use Saas\Project\Modules\User\Generics\Gateways\UserGateway;

class UserDeleteRule
{
    private int $id;

    private UserGateway $userDeleteGateway;

    public function __construct(
        UserGateway $userDeleteGateway,
        int $id
    ) {
        $this->userDeleteGateway = $userDeleteGateway;
        $this->id = $id;
    }

    public function apply(): bool
    {
        try {
            return $this->userDeleteGateway->delete($this->id);
        } catch (\Throwable $ex) {
            throw new UserDeleteException('Error delete user', 500, $ex);
        }
    }
}
