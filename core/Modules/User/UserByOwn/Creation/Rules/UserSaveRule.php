<?php

namespace Saas\Project\Modules\User\UserByOwn\Creation\Rules;

use Saas\Project\Modules\User\Exceptions\UserSaveException;
use Saas\Project\Modules\User\Generics\Entities\User;
use Saas\Project\Modules\User\Generics\Gateways\UserGateway;

class UserSaveRule
{
    private UserGateway $userSaveGateway;
    private User $user;

    public function __construct(
        UserGateway $userSaveGateway,
        User $user
    ) {
        $this->userSaveGateway = $userSaveGateway;
        $this->user = $user;
    }

    public function apply(): User
    {
        try {
            return $this->userSaveGateway->save($this->user);
        } catch (\Throwable $ex) {
            throw new UserSaveException('Error saving user', 500, $ex);
        }
    }
}
