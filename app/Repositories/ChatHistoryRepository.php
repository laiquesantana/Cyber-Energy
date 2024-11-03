<?php

namespace App\Repositories;

use App\Models\ChatHistory;
use Saas\Project\Modules\OpenAi\Chat\Creation\Gateways\SaveChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Delete\Gateways\DeleteChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory as ChatHistoryEntity;
use Saas\Project\Modules\OpenAi\Chat\Find\Gateways\RetrieveChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Generics\Collections\ChatHistoryCollection;
use Saas\Project\Modules\OpenAi\Chat\Update\Gateways\UpdateChatHistoryGateway;

class ChatHistoryRepository implements SaveChatHistoryGateway, RetrieveChatHistoryGateway, UpdateChatHistoryGateway,
                                       DeleteChatHistoryGateway
{
    public function save(ChatHistoryEntity $chatHistory): ChatHistoryEntity
    {
        ChatHistory::create([
            'user_input' => $chatHistory->getUserInput(),
            'ai_response' => $chatHistory->getAiResponse(),
        ]);
        return $chatHistory;
    }

    public function findById(int $id): ?ChatHistoryEntity
    {
        $model = ChatHistory::find($id);

        if ($model) {
            return new ChatHistoryEntity(
                $model->user_input,
                $model->ai_response,
                $model->id,
                $model->created_at,
                $model->updated_at
            );
        }

        return null;
    }

    public function findAll(): ChatHistoryCollection
    {
        $models = ChatHistory::all();

        $chatHistoryCollection = new ChatHistoryCollection();
        foreach ($models as $model) {
            $chatHistoryCollection->add(
                new ChatHistoryEntity(
                    $model->user_input,
                    $model->ai_response,
                    $model->id,
                    $model->created_at,
                    $model->updated_at
                )
            );
        }
        return $chatHistoryCollection;
    }

    public function update(ChatHistoryEntity $chatHistory): bool
    {
        $model = ChatHistory::find($chatHistory->getId());

        if ($model) {
            $model->user_input = $chatHistory->getUserInput();
            $model->ai_response = $chatHistory->getAiResponse();
            $model->save();

            return true;
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $model = ChatHistory::find($id);

        if ($model) {
            return $model->delete();
        }

        return false;
    }
}
