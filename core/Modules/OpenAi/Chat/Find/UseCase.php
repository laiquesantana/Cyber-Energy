<?php

namespace Saas\Project\Modules\OpenAi\Chat\Find;

use Saas\Project\Dependencies\Cache\CacheInterface;
use Saas\Project\Dependencies\Interfaces\LogInterface;
use Saas\Project\Modules\OpenAi\Chat\Find\Gateways\RetrieveChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Generics\Collections\ChatHistoryCollection;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;
use Exception;

class UseCase
{
    private RetrieveChatHistoryGateway $gateway;
    private CacheInterface $cache;
    private LogInterface $logger;

    public function __construct(
        RetrieveChatHistoryGateway $gateway,
        CacheInterface $cache,
        LogInterface $logger
    ) {
        $this->gateway = $gateway;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function getById(int $id): ?ChatHistory
    {
        $cacheKey = "chat_history:{$id}";

        try {
            $this->logger->info('Retrieving chat history by ID', ['id' => $id]);

            $chatHistory = $this->cache->get($cacheKey);

            if ($chatHistory) {
                $this->logger->info('Chat history found in cache', ['id' => $id]);
                return $chatHistory;
            }

            $chatHistory = $this->gateway->findById($id);

            if ($chatHistory) {
                $this->cache->set($cacheKey, $chatHistory, 300);
                $this->logger->info('Chat history cached after retrieval', ['id' => $id]);
            } else {
                $this->logger->warning('Chat history not found', ['id' => $id]);
            }

            return $chatHistory;
        } catch (Exception $e) {
            $this->logger->error('Error retrieving chat history by ID', ['id' => $id, 'exception' => $e]);
            throw $e;
        }
    }

    public function getAll(): ChatHistoryCollection
    {
        $cacheKey = "chat_history:all";

        try {
            $this->logger->info('Retrieving all chat histories');

            $chatHistories = $this->cache->get($cacheKey);

            if ($chatHistories) {
                $this->logger->info('Chat histories found in cache');
                return $chatHistories;
            }

            $chatHistories = $this->gateway->findAll();

            if ($chatHistories) {
                $this->cache->set($cacheKey, $chatHistories, 300);
                $this->logger->info('Chat histories cached after retrieval');
            } else {
                $this->logger->warning('No chat histories found');
            }

            return $chatHistories;
        } catch (Exception $e) {
            $this->logger->error('Error retrieving all chat histories', ['exception' => $e]);
            throw $e;
        }
    }
}
