<?php

namespace App\Http\Controllers\v1;

use App\Adapters\ConfigAdapter;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Saas\Project\Dependencies\Adapters\Logger\MonologLogAdapter;
use Saas\Project\Dependencies\Cache\CacheInterface;
use Saas\Project\Modules\OpenAi\Chat\Creation\UseCase as CreateChatHistoryUseCase;
use Saas\Project\Modules\OpenAi\Chat\Find\UseCase as RetrieveChatHistoryUseCase;
use Saas\Project\Modules\OpenAi\Chat\Update\UseCase as UpdateChatHistoryUseCase;
use Saas\Project\Modules\OpenAi\Chat\Delete\UseCase as DeleteChatHistoryUseCase;

class ChatController extends BaseController
{
    use ApiResponser;

    private ConfigAdapter $configAdapter;
    private MonologLogAdapter $logger;
    private CreateChatHistoryUseCase $createUseCase;
    private RetrieveChatHistoryUseCase $retrieveUseCase;
    private UpdateChatHistoryUseCase $updateUseCase;
    private DeleteChatHistoryUseCase $deleteUseCase;
    private CacheInterface $cache;

    public function __construct(
        CreateChatHistoryUseCase $createUseCase,
        RetrieveChatHistoryUseCase $retrieveUseCase,
        UpdateChatHistoryUseCase $updateUseCase,
        DeleteChatHistoryUseCase $deleteUseCase,
        CacheInterface $cache

    ) {
        $this->logger = new MonologLogAdapter();
        $this->configAdapter = new ConfigAdapter();
        $this->createUseCase = $createUseCase;
        $this->retrieveUseCase = $retrieveUseCase;
        $this->updateUseCase = $updateUseCase;
        $this->deleteUseCase = $deleteUseCase;
        $this->cache = $cache;

    }

    public function create(Request $request)
    {
        $userInput = $request->input('user_input');
        $response = $this->createUseCase->execute($userInput);

        return response()->json([
            'user_input' => $response->getUserInput(),
            'ai_response' => $response->getAiResponse(),
            'created_at' => $response->getCreatedAt(),
            'updated_at' => $response->getUpdatedAt(),
        ]);
    }

    public function index()
    {
        $chatHistories = $this->retrieveUseCase->getAll();
        $chatHistories = $chatHistories->all();
        $data = array_map(function ($chatHistory) {
            return [
                'id' => $chatHistory->getId(),
                'user_input' => $chatHistory->getUserInput(),
                'ai_response' => $chatHistory->getAiResponse(),
                'created_at' => $chatHistory->getCreatedAt(),
                'updated_at' => $chatHistory->getUpdatedAt(),
            ];
        }, $chatHistories);

        $data = array_values($data);

        return response()->json($data);
    }

    public function show($id)
    {
        $chatHistory = $this->retrieveUseCase->getById($id);

        if ($chatHistory) {
            return response()->json([
                'id' => $chatHistory->getId(),
                'user_input' => $chatHistory->getUserInput(),
                'ai_response' => $chatHistory->getAiResponse(),
                'created_at' => $chatHistory->getCreatedAt(),
                'updated_at' => $chatHistory->getUpdatedAt(),
            ]);
        } else {
            return response()->json(['message' => 'Chat history not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $chatHistory = $this->retrieveUseCase->getById($id);

        if (!$chatHistory) {
            return response()->json(['message' => 'Chat history not found'], 404);
        }

        $userInput = $request->input('user_input', $chatHistory->getUserInput());

        $chatHistory->setUserInput($userInput);

        $updated = $this->updateUseCase->execute($chatHistory);

        if ($updated) {
            return response()->json(['message' => 'Chat history updated successfully']);
        } else {
            return response()->json(['message' => 'Failed to update chat history'], 500);
        }
    }

    public function delete($id)
    {
        $deleted = $this->deleteUseCase->execute($id);

        if ($deleted) {
            return response()->json(['message' => 'Chat history deleted successfully']);
        } else {
            return response()->json(['message' => 'Chat history not found'], 404);
        }
    }
}
