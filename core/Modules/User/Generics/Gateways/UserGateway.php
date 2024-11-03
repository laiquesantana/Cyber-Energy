<?php

namespace Saas\Project\Modules\User\Generics\Gateways;

use Saas\Project\Modules\User\Generics\Entities\User;

interface UserGateway
{
    public function save(User $userEntity): User;

    public function update(User $userEntity): User;

    public function delete(int $id): bool;
}
