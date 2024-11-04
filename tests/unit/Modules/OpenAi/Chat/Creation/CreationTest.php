<?php

namespace unit\Modules\OpenAi\Chat\Creation;

use App\Repositories\ChatHistoryRepository;
use PHPUnit\Framework\TestCase;
use Saas\Project\Modules\OpenAi\Chat\Creation\UseCase;
use Saas\Project\Packages\OpenAi\Chat\Api as OpenAIGateway;
use Saas\Project\Dependencies\Cache\CacheInterface;
use Saas\Project\Dependencies\Interfaces\LogInterface;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;
use Exception;

class CreationTest extends TestCase
{
    public function testExecuteSuccess()
    {
        $openAIGatewayMock = $this->createMock(OpenAIGateway::class);
        $chatHistoryRepositoryMock = $this->createMock(ChatHistoryRepository::class);
        $cacheMock = $this->createMock(CacheInterface::class);
        $loggerMock = $this->createMock(LogInterface::class);

        $userInput = 'Tell me about renewable energy.';
        $aiResponse = 'Renewable energy comes from natural sources...';
        $verificationResponse = 'Yes';
        $filteredResponse = 'Renewable energy comes from natural sources...';

        $openAIGatewayMock->expects($this->exactly(2))
            ->method('getResponse')
            ->withConsecutive(
                [$userInput],
                ["Is the following answer related to the energy market? Answer only Yes or No. Answer:: {$aiResponse}"]
            )
            ->willReturnOnConsecutiveCalls($aiResponse, $verificationResponse);

        $chatHistoryRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($chatHistory) use ($userInput, $filteredResponse) {
                $this->assertInstanceOf(ChatHistory::class, $chatHistory);
                $this->assertEquals($userInput, $chatHistory->getUserInput());
                $this->assertEquals($filteredResponse, $chatHistory->getAiResponse());
                return true;
            }))
            ->willReturnCallback(function ($chatHistory) {
                $chatHistory->setId(1);
                return $chatHistory;
            });

        $cacheMock->expects($this->once())
            ->method('delete')
            ->with('chat_history:all');

        $loggerMock->expects($this->exactly(4))
            ->method('info')
            ->withConsecutive(
                ['Starting chat history creation', ['user_input' => $userInput]],
                ['Filtered AI response', ['filtered_response' => $filteredResponse]],
                ['Chat history saved', ['chat_history_id' => 1]],
                ['Cache invalidated for chat histories']
            );

        $useCase = new UseCase(
            $openAIGatewayMock,
            $chatHistoryRepositoryMock,
            $cacheMock,
            $loggerMock
        );

        $result = $useCase->execute($userInput);

        $this->assertInstanceOf(ChatHistory::class, $result);
        $this->assertEquals($userInput, $result->getUserInput());
        $this->assertEquals($filteredResponse, $result->getAiResponse());
        $this->assertEquals(1, $result->getId());
    }

    public function testExecuteFailure()
    {
        $openAIGatewayMock = $this->createMock(OpenAIGateway::class);
        $chatHistoryRepositoryMock = $this->createMock(ChatHistoryRepository::class);
        $cacheMock = $this->createMock(CacheInterface::class);
        $loggerMock = $this->createMock(LogInterface::class);

        $userInput = 'Tell me about renewable energy.';
        $exception = new Exception('OpenAI service error');

        $openAIGatewayMock->expects($this->once())
            ->method('getResponse')
            ->with($userInput)
            ->willThrowException($exception);

        $loggerMock->expects($this->once())
            ->method('error')
            ->with('Error during chat history creation', ['exception' => $exception]);

        $useCase = new UseCase(
            $openAIGatewayMock,
            $chatHistoryRepositoryMock,
            $cacheMock,
            $loggerMock
        );

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('OpenAI service error');

        $useCase->execute($userInput);
    }
}
