<?php

namespace Saas\Project\Modules\OpenAi\Chat\Creation;

use Saas\Project\Modules\OpenAi\Chat\Creation\Rules\FilterAIResponseRule;
use Saas\Project\Packages\OpenAi\Chat\Api as OpenAIGateway;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;
class UseCase
{
    private OpenAIGateway $gateway;
    private FilterAIResponseRule $filterRule;

    public function __construct(OpenAIGateway $gateway, FilterAIResponseRule $filterRule)
    {
        $this->gateway = $gateway;
        $this->filterRule = $filterRule;
    }

    public function execute(string $userInput): ChatHistory
    {
        $aiResponse = $this->gateway->getResponse($userInput);
        $filteredResponse = $this->filterRule->apply($aiResponse);

        return new ChatHistory($userInput, $filteredResponse);
    }
}
