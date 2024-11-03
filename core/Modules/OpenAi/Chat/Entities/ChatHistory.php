<?php

namespace Saas\Project\Modules\OpenAi\Chat\Entities;

class ChatHistory
{
    private ?int $id;
    private string $userInput;
    private string $aiResponse;
    private ?\DateTime $createdAt;
    private ?\DateTime $updatedAt;

    public function __construct(
        string $userInput,
        string $aiResponse,
        ?int $id = null,
        ?\DateTime $createdAt = null,
        ?\DateTime $updatedAt = null
    ) {
        $this->userInput = $userInput;
        $this->aiResponse = $aiResponse;
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserInput(): string
    {
        return $this->userInput;
    }

    public function getAiResponse(): string
    {
        return $this->aiResponse;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUserInput(string $userInput): void
    {
        $this->userInput = $userInput;
    }

    public function setAiResponse(string $aiResponse): void
    {
        $this->aiResponse = $aiResponse;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
