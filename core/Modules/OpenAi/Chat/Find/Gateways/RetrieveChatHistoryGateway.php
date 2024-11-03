<?php

namespace Saas\Project\Modules\OpenAi\Chat\Find\Gateways;


use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;
use Saas\Project\Modules\OpenAi\Chat\Generics\Collections\ChatHistoryCollection;

interface RetrieveChatHistoryGateway
{
    public function findById(int $id): ?ChatHistory;
    public function findAll(): ChatHistoryCollection;

}
