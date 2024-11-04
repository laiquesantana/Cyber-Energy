<?php

namespace Saas\Project\Modules\OpenAi\Chat\Delete;

use Saas\Project\Dependencies\Cache\CacheInterface;
use Saas\Project\Dependencies\Interfaces\LogInterface;
use Saas\Project\Modules\OpenAi\Chat\Delete\Gateways\DeleteChatHistoryGateway;
use Exception;

class UseCase
{
    private DeleteChatHistoryGateway $gateway;
    private CacheInterface $cache;
    private LogInterface $logger;

    public function __construct(
        DeleteChatHistoryGateway $gateway,
        CacheInterface $cache,
        LogInterface $logger
    ) {
        $this->gateway = $gateway;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function execute(int $id): bool
    {
        $cacheKey = "chat_history:{$id}";

        try {
            $this->logger->info('Attempting to delete chat history', ['id' => $id]);

            $deleted = $this->gateway->delete($id);

            if ($deleted) {
                $this->logger->info('Chat history successfully deleted', ['id' => $id]);

                $this->cache->delete($cacheKey);
                $this->logger->info('Cache invalidated for deleted chat history', ['cache_key' => $cacheKey]);
            } else {
                $this->logger->warning('Chat history not found for deletion', ['id' => $id]);
            }

            return $deleted;
        } catch (Exception $e) {
            $this->logger->error('Error during chat history deletion', ['id' => $id, 'exception' => $e]);
            throw $e;
        }
    }
}
