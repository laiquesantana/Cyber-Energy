<?php

namespace Saas\Project\Modules\OpenAi\Chat\Find;

use Saas\Project\Dependencies\Cache\CacheInterface;
use Saas\Project\Modules\OpenAi\Chat\Find\Gateways\RetrieveChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Generics\Collections\ChatHistoryCollection;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;

class UseCase
{
    private RetrieveChatHistoryGateway $gateway;
    private CacheInterface $cache;

    public function __construct(RetrieveChatHistoryGateway $gateway, CacheInterface $cache)
    {
        $this->gateway = $gateway;
        $this->cache = $cache;
    }

    public function getById(int $id): ?ChatHistory
    {
        $cacheKey = "chat_history:{$id}";
        $chatHistory = $this->cache->get($cacheKey);

        if ($chatHistory) {
            return $chatHistory;
        }

        $chatHistory = $this->gateway->findById($id);

        if ($chatHistory) {
            $this->cache->set($cacheKey, $chatHistory, 300);
        }

        return $chatHistory;
    }

    public function getAll(): ChatHistoryCollection
    {
        $cacheKey = "chat_history:all";
        $chatHistories = $this->cache->get($cacheKey);


        if ($chatHistories) {
            return $chatHistories;
        }
        $chatHistories = $this->gateway->findAll();

        if ($chatHistories) {
            $this->cache->set($cacheKey, $chatHistories, 300);
        }

        return $chatHistories;
    }
}
