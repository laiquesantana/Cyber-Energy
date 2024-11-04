<?php

namespace unit\Modules\OpenAi\Chat\Delete;

use PHPUnit\Framework\TestCase;
use Saas\Project\Modules\OpenAi\Chat\Delete\UseCase;
use Saas\Project\Modules\OpenAi\Chat\Delete\Gateways\DeleteChatHistoryGateway;
use Saas\Project\Dependencies\Cache\CacheInterface;
use Saas\Project\Dependencies\Interfaces\LogInterface;
use Exception;

class DeleteTest extends TestCase
{
    public function testExecuteSuccess()
    {
        $deleteGatewayMock = $this->createMock(DeleteChatHistoryGateway::class);
        $cacheMock = $this->createMock(CacheInterface::class);
        $loggerMock = $this->createMock(LogInterface::class);

        $chatHistoryId = 1;
        $cacheKey = "chat_history:{$chatHistoryId}";

        $deleteGatewayMock->expects($this->once())
            ->method('delete')
            ->with($chatHistoryId)
            ->willReturn(true);

        $cacheMock->expects($this->once())
            ->method('delete')
            ->with($cacheKey);

        $loggerMock->expects($this->exactly(3))
            ->method('info')
            ->withConsecutive(
                ['Attempting to delete chat history', ['id' => $chatHistoryId]],
                ['Chat history successfully deleted', ['id' => $chatHistoryId]],
                ['Cache invalidated for deleted chat history', ['cache_key' => $cacheKey]]
            );

        $useCase = new UseCase(
            $deleteGatewayMock,
            $cacheMock,
            $loggerMock
        );

        $result = $useCase->execute($chatHistoryId);

        $this->assertTrue($result);
    }

    public function testExecuteNotFound()
    {
        $deleteGatewayMock = $this->createMock(DeleteChatHistoryGateway::class);
        $cacheMock = $this->createMock(CacheInterface::class);
        $loggerMock = $this->createMock(LogInterface::class);

        $chatHistoryId = 2;

        $deleteGatewayMock->expects($this->once())
            ->method('delete')
            ->with($chatHistoryId)
            ->willReturn(false);

        $cacheMock->expects($this->never())
            ->method('delete');

        $loggerMock->expects($this->once())
            ->method('info')
            ->with('Attempting to delete chat history', ['id' => $chatHistoryId]);

        $loggerMock->expects($this->once())
            ->method('warning')
            ->with('Chat history not found for deletion', ['id' => $chatHistoryId]);

        $useCase = new UseCase(
            $deleteGatewayMock,
            $cacheMock,
            $loggerMock
        );

        $result = $useCase->execute($chatHistoryId);

        $this->assertFalse($result);
    }

    public function testExecuteFailure()
    {
        $deleteGatewayMock = $this->createMock(DeleteChatHistoryGateway::class);
        $cacheMock = $this->createMock(CacheInterface::class);
        $loggerMock = $this->createMock(LogInterface::class);

        $chatHistoryId = 3;
        $exception = new Exception('Deletion error');

        $deleteGatewayMock->expects($this->once())
            ->method('delete')
            ->with($chatHistoryId)
            ->willThrowException($exception);

        $loggerMock->expects($this->once())
            ->method('error')
            ->with('Error during chat history deletion', ['id' => $chatHistoryId, 'exception' => $exception]);

        $useCase = new UseCase(
            $deleteGatewayMock,
            $cacheMock,
            $loggerMock
        );

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Deletion error');

        $useCase->execute($chatHistoryId);
    }
}
