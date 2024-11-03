<?php

namespace Saas\Project\Modules\User\UserByOwn\Creation\Builders;

use Saas\Project\Modules\User\Generics\Entities\Status;
use Saas\Project\Modules\User\UserByOwn\Creation\Responses\Response;
use Saas\Project\Modules\User\UserByOwn\Creation\Rules\UserSaveRule;

class Builder
{
    private UserSaveRule $userSaveRule;

    public function withUserSaveRule(UserSaveRule $userSaveRule): Builder
    {
        $this->userSaveRule = $userSaveRule;

        return $this;
    }

    public function build(): Response
    {
        $user = $this->userSaveRule->apply();

        return (new Response())
            ->setStatus(
                (new Status())
                    ->setCode(201)
                    ->setMessage('User created')
            )
            ->setData([
                'uuid' => $user->getUuid(),
            ])
            ->setMeta(
                [
                    'total' => 1,
                ]
            );
    }
}
