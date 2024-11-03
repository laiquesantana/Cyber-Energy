<?php
declare(strict_types=1);

namespace Saas\Project\Modules\OpenAi\Chat\Entities;

class ChatHistory
{
    private string $userInput;
    private string $aiResponse;

    public function __construct(string $userInput, string $aiResponse)
    {
        $this->userInput = $userInput;
        $this->aiResponse = $aiResponse;
    }

    public function getUserInput(): string
    {
        return $this->userInput;
    }

    public function getAIResponse(): string
    {
        return $this->aiResponse;
    }
}
