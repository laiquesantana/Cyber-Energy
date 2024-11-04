<?php
namespace Saas\Project\Modules\OpenAi\Chat\Update;

use Saas\Project\Dependencies\Cache\CacheInterface;
use Saas\Project\Dependencies\Interfaces\LogInterface;
use Saas\Project\Modules\OpenAi\Chat\Update\Gateways\UpdateChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;
use Saas\Project\Packages\OpenAi\Chat\Api as OpenAIGateway;
use Saas\Project\Modules\OpenAi\Chat\Creation\Rules\FilterAIResponseRule;

class UseCase
{
    private UpdateChatHistoryGateway $gateway;
    private OpenAIGateway $openAIGateway;
    private CacheInterface $cache;
    private LogInterface $logger;

    public function __construct(
        UpdateChatHistoryGateway $gateway,
        OpenAIGateway $openAIGateway,
        CacheInterface $cache,
        LogInterface $logger
    ) {
        $this->gateway = $gateway;
        $this->openAIGateway = $openAIGateway;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function execute(ChatHistory $chatHistory): bool
    {
        try {
            $this->logger->info('Starting chat history update', ['chat_history_id' => $chatHistory->getId()]);

            $aiResponse = $this->openAIGateway->getResponse($chatHistory->getUserInput());

            $verificationPrompt = "Is the following answer related to the energy market? Answer only Yes or No. Answer:: {$aiResponse}";
            $isRelevant = $this->openAIGateway->getResponse($verificationPrompt);

            $filteredResponse = (new FilterAIResponseRule())->apply($aiResponse, $isRelevant);
            $this->logger->info('Filtered AI response for update', ['filtered_response' => $filteredResponse]);

            $chatHistory->setAiResponse($filteredResponse);

            $updated = $this->gateway->update($chatHistory);

            if ($updated) {
                $this->invalidateCache($chatHistory);
                $this->logger->info('Cache invalidated for updated chat history', ['chat_history_id' => $chatHistory->getId()]);
            }

            return $updated;
        } catch (\Exception $e) {
            $this->logger->error('Error during chat history update', ['exception' => $e]);
            throw $e;
        }
    }

    private function invalidateCache(ChatHistory $chatHistory): void
    {
        $this->cache->delete("chat_history:{$chatHistory->getId()}");
        $this->cache->delete('chat_history:all');
    }
}
