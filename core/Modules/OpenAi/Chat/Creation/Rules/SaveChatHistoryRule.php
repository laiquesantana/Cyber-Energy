<?php

namespace Saas\Project\Modules\OpenAi\Chat\Creation\Rules;

use App\Repositories\ChatHistoryRepository;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;

class SaveChatHistoryRule
{
    private ChatHistoryRepository $repository;

    public function __construct(ChatHistoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function apply(ChatHistory $chatHistory): void
    {
        $this->repository->save($chatHistory);
    }
}
