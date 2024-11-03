<?php

namespace Saas\Project\Modules\OpenAi\Chat\Delete\Requests;

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
