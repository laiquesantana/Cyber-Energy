<?php

namespace Saas\Project\Modules\User\UserByOwn\Delete;

use Saas\Project\Dependencies\Interfaces\LogInterface;
use Saas\Project\Modules\User\Exceptions\UserDeleteException;
use Saas\Project\Modules\User\Generics\Entities\Status;
use Saas\Project\Modules\User\Generics\Gateways\UserGateway;
use Saas\Project\Modules\User\UserByOwn\Delete\Builders\Builder;
use Saas\Project\Modules\User\UserByOwn\Delete\Requests\Request;
use Saas\Project\Modules\User\UserByOwn\Delete\Responses\Response;
use Saas\Project\Modules\User\UserByOwn\Delete\Responses\ResponseInterface;
use Saas\Project\Modules\User\UserByOwn\Delete\Rules\UserDeleteRule;

class UseCase
{
    private UserGateway $userGateway;

    private ResponseInterface $response;

    private const LOG_PREFIX = '[User/UserByOwn/Creation::UseCase] ';

    private LogInterface $logger;

    public function __construct(
        UserGateway $userGateway,
        LogInterface $logger

    ) {
        $this->userGateway = $userGateway;
        $this->logger = $logger;
    }

    public function execute(Request $request): void
    {
        try {
            $this->response = (new Builder())
                ->withUserDeleteRule(
                    new UserDeleteRule($this->userGateway, $request->getId())
                )
                ->build();
        } catch (UserDeleteException $exception) {
            $this->logger->error(
                self::LOG_PREFIX.'A error occurred when trying delete user',
                [
                    'exception' => get_class($exception),
                    'message' => $exception->getMessage(),
                    'previous' => [
                        'exception' => $exception->getPrevious() ? get_class($exception->getPrevious()) : null,
                        'message' => $exception->getPrevious() ? $exception->getPrevious()->getMessage() : null,
                    ],
                    'data' => [
                        'id' => $request->getId(),
                    ],
                ]
            );

            $this->response = (new Response())
                ->setStatus(
                    (new Status())
                        ->setCode($exception->getCode())
                        ->setMessage('Error when trying delete user')
                )
                ->setData('')
                ->setError('User delete error')
                ->setMeta(
                    [
                        'total' => 1,
                    ]
                );
        } catch (\Throwable $exception) {
            $this->logger->error(
                self::LOG_PREFIX.'Generic error',
                [
                    'exception' => get_class($exception),
                    'message' => $exception->getMessage(),
                    'previous' => [
                        'exception' => $exception->getPrevious() ? get_class($exception->getPrevious()) : null,
                        'message' => $exception->getPrevious() ? $exception->getPrevious()->getMessage() : null,
                    ],
                    'data' => [
                        'user' => $request->getId(),
                    ],
                ]
            );

            $this->response = (new Response())
                ->setStatus(
                    (new Status())
                        ->setCode($exception->getCode())
                        ->setMessage('Generic Error when trying delete user')
                )
                ->setData('')
                ->setError('Generic Error')
                ->setMeta(
                    [
                        'total' => 1,
                    ]
                );
        }
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
