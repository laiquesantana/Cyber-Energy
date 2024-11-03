<?php

namespace Saas\Project\Modules\Generics\Responses\Error;


use Saas\Project\Modules\Generics\Entities\Status;
use Saas\Project\Modules\Generics\Responses\ResponseInterface;

class Response implements ResponseInterface
{
    private Status $status;
    private \Throwable $exception;

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): Response
    {
        $this->status = $status;
        return $this;
    }

    public function getException(): \Throwable
    {
        return $this->exception;
    }

    public function setException(\Throwable $exception): Response
    {
        $this->exception = $exception;
        return $this;
    }
}
