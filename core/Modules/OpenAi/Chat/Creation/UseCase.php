<?php

namespace Saas\Project\Modules\OpenAi\Chat\Creation;

use Saas\Project\Modules\OpenAi\Chat\Creation\Gateways\SaveChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Creation\Rules\FilterAIResponseRule;
use Saas\Project\Modules\OpenAi\Chat\Creation\Rules\SaveChatHistoryRule;
use Saas\Project\Packages\OpenAi\Chat\Api as OpenAIGateway;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;

class UseCase
{
    private OpenAIGateway $gateway;
    private SaveChatHistoryGateway $saveChatHistoryGateway;

    public function __construct(
        OpenAIGateway $gateway,
        SaveChatHistoryGateway $saveChatHistoryGateway
    ) {
        $this->gateway = $gateway;
        $this->saveChatHistoryGateway = $saveChatHistoryGateway;
    }

    public function execute(string $userInput): ChatHistory
    {
        $aiResponse = $this->gateway->getResponse($userInput);
        $verificationPrompt = "Is the following answer related to the energy market? Answer only Yes or No. Answer:: {$aiResponse}";
        $isRelevant = $this->gateway->getResponse($verificationPrompt);

        $filteredResponse = (new FilterAIResponseRule())->apply($aiResponse,$isRelevant);

        $chatHistory = new ChatHistory($userInput, $filteredResponse);
        (new SaveChatHistoryRule($this->saveChatHistoryGateway))->apply($chatHistory);

        return $chatHistory;
    }
}
