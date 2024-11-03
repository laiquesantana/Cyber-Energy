<?php

namespace App\Repositories;

use App\Models\ChatHistory;
use Illuminate\Database\Eloquent\Collection;
use Saas\Project\Modules\OpenAi\Chat\Creation\Gateways\SaveChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory as ChatHistoryEntity;
class ChatHistoryRepository implements SaveChatHistoryGateway
{
    public function save(ChatHistoryEntity $chatHistory): ChatHistoryEntity
    {
        ChatHistory::create([
            'user_input' => $chatHistory->getUserInput(),
            'ai_response' => $chatHistory->getAiResponse(),
        ]);
        return $chatHistory;
    }

    public function findById(int $id): ?ChatHistory
    {
        return ChatHistory::find($id);
    }

    public function findAll(): Collection
    {
        return ChatHistory::all();
    }

    public function update(int $id, array $data): bool
    {
        $chatHistory = $this->findById($id);

        if ($chatHistory) {
            return $chatHistory->update($data);
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $chatHistory = $this->findById($id);

        if ($chatHistory) {
            return $chatHistory->delete();
        }

        return false;
    }
}
