<?php

namespace Saas\Project\Modules\User\UserByOwn\Presenters\Responses;

use Saas\Project\Modules\User\UserByOwn\Creation\Responses\Response;
use Saas\Project\Modules\User\UserByOwn\Presenters\PresenterInterface;

class ResponsePresenter implements PresenterInterface
{
    private Response $response;

    private array $presenter;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function present(): PresenterInterface
    {
        $this->presenter = [
            'status' => [
                'code' => $this->response->getStatus()->getCode(),
                'message' => $this->response->getStatus()->getMessage(),
            ],
            'data' => ($this->response->getData()),
            'meta' => $this->response->getMeta(),
        ];

        return $this;
    }

    public function toArray(): array
    {
        return $this->presenter;
    }
}
