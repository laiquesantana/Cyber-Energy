<?php

namespace Saas\Project\Modules\User\Generics\Entities;

class Status
{
    private int $code;

    private string $message;

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): Status
    {
        $this->code = $code;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): Status
    {
        $this->message = $message;

        return $this;
    }
}
