<?php

namespace App\Http\Controllers\v1;

use App\Adapters\ConfigAdapter;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Saas\Project\Dependencies\Adapters\Logger\MonologLogAdapter;
use Saas\Project\Modules\OpenAi\Chat\Creation\UseCase as CreateChatHistoryUseCase;

class ChatController extends BaseController
{
    use ApiResponser;

    private ConfigAdapter $configAdapter;
    private MonologLogAdapter $logger;
    private CreateChatHistoryUseCase $useCase;

    public function __construct(CreateChatHistoryUseCase $useCase)

    {
        $this->logger = new MonologLogAdapter();
        $this->configAdapter = new ConfigAdapter();
        $this->useCase = $useCase;

    }

    public function create(Request $request)
    {
        $userInput = $request->input('user_input');
        $response = $this->useCase->execute($userInput);

        return response()->json([
            'user_input' => $userInput,
            'ai_response' => $response->getAIResponse(),
        ]);
    }

}

