<?php

namespace Saas\Project\Modules\OpenAi\Chat\Delete;

use Saas\Project\Modules\OpenAi\Chat\Delete\Gateways\DeleteChatHistoryGateway;

class UseCase
{
    private DeleteChatHistoryGateway $gateway;

    public function __construct(DeleteChatHistoryGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function execute(int $id): bool
    {
        return $this->gateway->delete($id);
    }
}
