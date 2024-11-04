<?php

namespace Saas\Project\Modules\OpenAi\Chat\Update;

use Saas\Project\Dependencies\Cache\CacheInterface;
use Saas\Project\Modules\OpenAi\Chat\Update\Gateways\UpdateChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;
use Saas\Project\Packages\OpenAi\Chat\Api as OpenAIGateway;
use Saas\Project\Modules\OpenAi\Chat\Creation\Rules\FilterAIResponseRule;

class UseCase
{
    private UpdateChatHistoryGateway $gateway;
    private OpenAIGateway $openAIGateway;
    private CacheInterface $cache;

    public function __construct(
        UpdateChatHistoryGateway $gateway,
        OpenAIGateway $openAIGateway,
        CacheInterface $cache
    ) {
        $this->gateway = $gateway;
        $this->openAIGateway = $openAIGateway;
        $this->cache = $cache;
    }

    public function execute(ChatHistory $chatHistory): bool
    {
        $aiResponse = $this->openAIGateway->getResponse($chatHistory->getUserInput());

        $verificationPrompt = "Is the following answer related to the energy market? Answer only Yes or No. Answer:: {$aiResponse}";
        $isRelevant = $this->openAIGateway->getResponse($verificationPrompt);

        $filteredResponse = (new FilterAIResponseRule())->apply($aiResponse, $isRelevant);

        $chatHistory->setAiResponse($filteredResponse);

        $updated = $this->gateway->update($chatHistory);

        if ($updated) {
            $this->invalidateCache($chatHistory);
        }

        return $updated;
    }

    private function invalidateCache(ChatHistory $chatHistory): void
    {

        $this->cache->delete("chat_history:{$chatHistory->getId()}");
    }
}
