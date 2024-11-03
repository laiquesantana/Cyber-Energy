<?php

namespace Saas\Project\Modules\OpenAi\Chat\Update\Gateways;


use Saas\Project\Modules\Generics\Collection\Collection;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;

interface UpdateChatHistoryGateway
{
    public function update(ChatHistory $chatHistory): bool;


}
