<?php

namespace Saas\Project\Modules\OpenAi\Chat\Creation\Builders;

use Saas\Project\Modules\User\Generics\Entities\Status;
use Saas\Project\Modules\OpenAi\Chat\Creation\Responses\Response;
use Saas\Project\Modules\OpenAi\Chat\Creation\Rules\FilterAIResponseRule;

class Builder
{
    private FilterAIResponseRule $userSaveRule;

    public function withUserSaveRule(FilterAIResponseRule $userSaveRule): Builder
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
