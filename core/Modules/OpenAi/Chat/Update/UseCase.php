<?php

namespace Saas\Project\Modules\OpenAi\Chat\Update;

use Saas\Project\Modules\OpenAi\Chat\Update\Gateways\UpdateChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;

class UseCase
{
    private UpdateChatHistoryGateway $gateway;

    public function __construct(UpdateChatHistoryGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function execute(ChatHistory $chatHistory): bool
    {
        return $this->gateway->update($chatHistory);
    }

}
