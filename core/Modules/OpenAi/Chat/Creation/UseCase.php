<?php
namespace Saas\Project\Modules\OpenAi\Chat\Creation;

use Saas\Project\Dependencies\Cache\CacheInterface;
use Saas\Project\Modules\OpenAi\Chat\Creation\Gateways\SaveChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Creation\Rules\FilterAIResponseRule;
use Saas\Project\Modules\OpenAi\Chat\Creation\Rules\SaveChatHistoryRule;
use Saas\Project\Packages\OpenAi\Chat\Api as OpenAIGateway;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;

class UseCase
{
    private OpenAIGateway $gateway;
    private SaveChatHistoryGateway $saveChatHistoryGateway;
    private CacheInterface $cache;

    public function __construct(
        OpenAIGateway $gateway,
        SaveChatHistoryGateway $saveChatHistoryGateway,
        CacheInterface $cache
    ) {
        $this->gateway = $gateway;
        $this->saveChatHistoryGateway = $saveChatHistoryGateway;
        $this->cache = $cache;
    }

    public function execute(string $userInput): ChatHistory
    {
        $aiResponse = $this->gateway->getResponse($userInput);
        $verificationPrompt = "Is the following answer related to the energy market? Answer only Yes or No. Answer:: {$aiResponse}";
        $isRelevant = $this->gateway->getResponse($verificationPrompt);

        $filteredResponse = (new FilterAIResponseRule())->apply($aiResponse, $isRelevant);

        $chatHistory = new ChatHistory($userInput, $filteredResponse);
        (new SaveChatHistoryRule($this->saveChatHistoryGateway))->apply($chatHistory);

        $this->invalidateCache();

        return $chatHistory;
    }

    private function invalidateCache()
    {
        $this->cache->delete('chat_history:all');
    }
}
