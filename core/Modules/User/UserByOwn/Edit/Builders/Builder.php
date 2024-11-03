<?php

namespace Saas\Project\Modules\User\UserByOwn\Edit\Builders;

use Saas\Project\Modules\User\Generics\Entities\Status;
use Saas\Project\Modules\User\UserByOwn\Edit\Responses\Response;
use Saas\Project\Modules\User\UserByOwn\Edit\Rules\UserEditRule;

class Builder
{
    private UserEditRule $userEditRule;

    public function withUserEditRule(UserEditRule $userEditRule): Builder
    {
        $this->userEditRule = $userEditRule;

        return $this;
    }

    public function build(): Response
    {
        $user = $this->userEditRule->apply();

        return (new Response())
            ->setStatus(
                (new Status())
                    ->setCode(200)
                    ->setMessage('User updated')
            )
            ->setData([
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
                'email' => $user->getEmail(),
                'uuid' => $user->getUuid(),

            ])
            ->setMeta(
                [
                    'total' => 1,
                ]
            );
    }
}
