<?php

namespace Saas\Project\Modules\OpenAi\Chat\Creation\Rules;

use Saas\Project\Modules\User\Exceptions\UserSaveException;
use Saas\Project\Modules\User\Generics\Entities\User;
use Saas\Project\Modules\User\Generics\Gateways\UserGateway;

class FilterAIResponseRule
{
    private string $filter;

    public function __construct(string $filter = 'energy')
    {
        $this->filter = $filter;
    }

    public function apply(string $response): string
    {
        return str_contains(strtolower($response), $this->filter) ? $response : 'Response not related to energy market';
    }
}
