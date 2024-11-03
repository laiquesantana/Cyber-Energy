<?php

namespace Saas\Project\Modules\OpenAi\Chat\Creation\Responses;

use Saas\Project\Modules\User\Generics\Entities\Status;
use Saas\Project\Modules\User\UserByOwn\Presenters\PresenterInterface;
use Saas\Project\Modules\User\UserByOwn\Presenters\Responses\ResponsePresenter;

class Response implements ResponseInterface
{
    private Status $status;
    private array $data;
    private array $meta;
    private string $error;

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): Response
    {
        $this->status = $status;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): ?Response
    {
        $this->data = $data;

        return $this;
    }

    public function setMeta(array $meta): Response
    {
        $this->meta = $meta;

        return $this;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function setError(string $error): Response
    {
        $this->error = $error;

        return $this;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }
}
