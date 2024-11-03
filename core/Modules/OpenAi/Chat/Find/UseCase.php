<?php

namespace Saas\Project\Modules\OpenAi\Chat\Find;

use Saas\Project\Modules\OpenAi\Chat\Find\Gateways\RetrieveChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Generics\Collections\ChatHistoryCollection;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;

class UseCase
{
    private RetrieveChatHistoryGateway $gateway;

    public function __construct(RetrieveChatHistoryGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function getById(int $id): ?ChatHistory
    {
        return $this->gateway->findById($id);
    }

    public function getAll(): ChatHistoryCollection
    {
        return $this->gateway->findAll();
    }
}
