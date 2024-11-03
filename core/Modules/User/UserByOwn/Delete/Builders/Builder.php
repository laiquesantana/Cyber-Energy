<?php

namespace Saas\Project\Modules\User\UserByOwn\Delete\Builders;

use Saas\Project\Modules\User\Generics\Entities\Status;
use Saas\Project\Modules\User\UserByOwn\Delete\Responses\Response;
use Saas\Project\Modules\User\UserByOwn\Delete\Rules\UserDeleteRule;

class Builder
{
    private UserDeleteRule $userDeleteRule;

    public function withUserDeleteRule(UserDeleteRule $userDeleteRule): Builder
    {
        $this->userDeleteRule = $userDeleteRule;

        return $this;
    }

    public function build(): Response
    {
        $user = $this->userDeleteRule->apply();

        return (new Response())
            ->setStatus(
                (new Status())
                    ->setCode(201)
                    ->setMessage('User deleted')
            )
            ->setData('')
            ->setMeta(
                [
                    'total' => 1,
                ]
            );
    }
}
