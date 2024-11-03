<?php

namespace Saas\Project\Modules\OpenAi\Chat\Creation\Gateways;


use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;

interface SaveChatHistoryGateway
{
    public function save(ChatHistory $chatHistory): ChatHistory;


}
