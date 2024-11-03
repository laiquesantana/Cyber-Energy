<?php

namespace Saas\Project\Modules\OpenAi\Chat\Delete\Gateways;

use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;

interface DeleteChatHistoryGateway
{
    public function delete(int $id): bool;
}
