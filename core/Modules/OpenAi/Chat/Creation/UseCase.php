<?php
namespace Saas\Project\Modules\OpenAi\Chat\Creation;

use Saas\Project\Dependencies\Cache\CacheInterface;
use Saas\Project\Dependencies\Interfaces\LogInterface;
use Saas\Project\Modules\OpenAi\Chat\Creation\Gateways\SaveChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Creation\Rules\FilterAIResponseRule;
use Saas\Project\Modules\OpenAi\Chat\Creation\Rules\SaveChatHistoryRule;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;
use Saas\Project\Packages\OpenAi\Chat\Api as OpenAIGateway;

class UseCase
{
    private OpenAIGateway $gateway;
    private SaveChatHistoryGateway $saveChatHistoryGateway;
    private CacheInterface $cache;
    private LogInterface $logger;

    public function __construct(
        OpenAIGateway $gateway,
        SaveChatHistoryGateway $saveChatHistoryGateway,
        CacheInterface $cache,
        LogInterface $logger
    ) {
        $this->gateway = $gateway;
        $this->saveChatHistoryGateway = $saveChatHistoryGateway;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function execute(string $userInput): ChatHistory
    {
        try {
            $this->logger->info('Starting chat history creation', ['user_input' => $userInput]);

            $aiResponse = $this->gateway->getResponse($userInput);

            $verificationPrompt = "Is the following answer related to the energy market? Answer only Yes or No. Answer:: {$aiResponse}";
            $isRelevant = $this->gateway->getResponse($verificationPrompt);

            $filteredResponse = (new FilterAIResponseRule())->apply($aiResponse, $isRelevant);
            $this->logger->info('Filtered AI response', ['filtered_response' => $filteredResponse]);

            $chatHistory = new ChatHistory($userInput, $filteredResponse);
            (new SaveChatHistoryRule($this->saveChatHistoryGateway))->apply($chatHistory);
            $this->logger->info('Chat history saved', ['chat_history_id' => $chatHistory->getId()]);

            $this->invalidateCache();
            $this->logger->info('Cache invalidated for chat histories');

            return $chatHistory;
        } catch (\Exception $e) {
            $this->logger->error('Error during chat history creation', ['exception' => $e]);
            throw $e;
        }
    }

    private function invalidateCache(): void
    {
        $this->cache->delete('chat_history:all');
    }
}
