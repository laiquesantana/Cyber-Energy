<?php

declare(strict_types=1);


namespace Saas\Project\Modules\OpenAi\Chat\Generics\Collections;
use Saas\Project\Modules\Generics\Collection\Collection;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;

class ChatHistoryCollection extends Collection
{
    public function add(ChatHistory $chatHistory): ChatHistoryCollection
    {
        $this->collector[$chatHistory->getId()] = $chatHistory;
        return $this;
    }

    public function getByIdentifier(string $identifier): ChatHistory
    {
        return $this->collector[$identifier];
    }

    public function remove(string $identifier): void
    {
        unset($this->collector[$identifier]);
    }
}
