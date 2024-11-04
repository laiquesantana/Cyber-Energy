<?php

namespace unit\Modules\OpenAi\Chat\Update;

use PHPUnit\Framework\TestCase;
use Saas\Project\Modules\OpenAi\Chat\Update\UseCase;
use Saas\Project\Packages\OpenAi\Chat\Api as OpenAIGateway;
use Saas\Project\Modules\OpenAi\Chat\Update\Gateways\UpdateChatHistoryGateway;
use Saas\Project\Modules\OpenAi\Chat\Entities\ChatHistory;
use Saas\Project\Dependencies\Cache\CacheInterface;
use Saas\Project\Dependencies\Interfaces\LogInterface;
use Exception;

class UpdateTest extends TestCase
{
    public function testExecuteSuccess()
    {
        $updateGatewayMock = $this->createMock(UpdateChatHistoryGateway::class);
        $openAIGatewayMock = $this->createMock(OpenAIGateway::class);
        $cacheMock = $this->createMock(CacheInterface::class);
        $loggerMock = $this->createMock(LogInterface::class);

        $chatHistory = new ChatHistory('Tell me about solar energy.', 'Initial AI response.');
        $chatHistory->setId(1);
        $aiResponse = 'Solar energy is harnessed from the sun.';
        $verificationResponse = 'Yes';
        $filteredResponse = 'Solar energy is harnessed from the sun.';

        $openAIGatewayMock->expects($this->exactly(2))
            ->method('getResponse')
            ->withConsecutive(
                [$chatHistory->getUserInput()],
                ["Is the following answer related to the energy market? Answer only Yes or No. Answer:: {$aiResponse}"]
            )
            ->willReturnOnConsecutiveCalls($aiResponse, $verificationResponse);

        $updateGatewayMock->expects($this->once())
            ->method('update')
            ->with(
                $this->callback(function ($updatedChatHistory) use ($filteredResponse) {
                    $this->assertEquals($filteredResponse, $updatedChatHistory->getAiResponse());
                    return true;
                })
            )
            ->willReturn(true);

        $cacheMock->expects($this->exactly(2))
            ->method('delete')
            ->withConsecutive(
                ["chat_history:{$chatHistory->getId()}"],
                ['chat_history:all']
            );

        $loggerMock->expects($this->exactly(3))
            ->method('info')
            ->withConsecutive(
                ['Starting chat history update', ['chat_history_id' => $chatHistory->getId()]],
                ['Filtered AI response for update', ['filtered_response' => $filteredResponse]],
                ['Cache invalidated for updated chat history', ['chat_history_id' => $chatHistory->getId()]]
            );

        $useCase = new UseCase(
            $updateGatewayMock,
            $openAIGatewayMock,
            $cacheMock,
            $loggerMock
        );

        $result = $useCase->execute($chatHistory);

        $this->assertTrue($result);
        $this->assertEquals($filteredResponse, $chatHistory->getAiResponse());
    }

    public function testExecuteFailure()
    {
        $updateGatewayMock = $this->createMock(UpdateChatHistoryGateway::class);
        $openAIGatewayMock = $this->createMock(OpenAIGateway::class);
        $cacheMock = $this->createMock(CacheInterface::class);
        $loggerMock = $this->createMock(LogInterface::class);

        $chatHistory = new ChatHistory('Tell me about wind energy.', 'Initial AI response.');
        $chatHistory->setId(2);
        $exception = new Exception('Update failed');

        $openAIGatewayMock->expects($this->once())
            ->method('getResponse')
            ->with($chatHistory->getUserInput())
            ->willThrowException($exception);

        $loggerMock->expects($this->once())
            ->method('error')
            ->with('Error during chat history update', ['exception' => $exception]);

        $useCase = new UseCase(
            $updateGatewayMock,
            $openAIGatewayMock,
            $cacheMock,
            $loggerMock
        );

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Update failed');

        $useCase->execute($chatHistory);
    }
}
