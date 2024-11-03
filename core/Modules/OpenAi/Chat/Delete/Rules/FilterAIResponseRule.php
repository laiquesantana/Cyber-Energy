<?php

namespace Saas\Project\Modules\OpenAi\Chat\Delete\Rules;

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

    public function apply(string $response, string $isRelevant): string
    {
        if (strtolower(trim($isRelevant)) === 'yes') {
            $filteredResponse = $response;
        } else {
            $filteredResponse = 'Sorry, I am not able to help you with that.';
        }
        return $filteredResponse;
    }
}
