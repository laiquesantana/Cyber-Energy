<?php

namespace Saas\Project\Modules\User\UserByOwn\Edit;

use Saas\Project\Dependencies\Interfaces\LogInterface;
use Saas\Project\Modules\User\Exceptions\UserEditException;
use Saas\Project\Modules\User\Generics\Entities\Status;
use Saas\Project\Modules\User\Generics\Gateways\UserGateway;
use Saas\Project\Modules\User\UserByOwn\Edit\Builders\Builder;
use Saas\Project\Modules\User\UserByOwn\Edit\Requests\Request;
use Saas\Project\Modules\User\UserByOwn\Edit\Responses\Response;
use Saas\Project\Modules\User\UserByOwn\Edit\Responses\ResponseInterface;
use Saas\Project\Modules\User\UserByOwn\Edit\Rules\UserEditRule;

class UseCase
{
    private UserGateway $userGateway;

    private ResponseInterface $response;

    private const LOG_PREFIX = '[User/Creation::UseCase] ';

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
                ->withUserEditRule(
                    new UserEditRule($this->userGateway, $request->getUser())
                )
                ->build();
        } catch (UserEditException $exception) {
            $this->logger->error(
                self::LOG_PREFIX.'A error occurred when trying update user details',
                [
                    'exception' => get_class($exception),
                    'message' => $exception->getMessage(),
                    'previous' => [
                        'exception' => $exception->getPrevious() ? get_class($exception->getPrevious()) : null,
                        'message' => $exception->getPrevious() ? $exception->getPrevious()->getMessage() : null,
                    ],
                    'data' => [
                        'user' => $request->getUser(),
                    ],
                ]
            );

            $this->response = (new Response())
                ->setStatus(
                    (new Status())
                        ->setCode($exception->getCode())
                        ->setMessage('Error when trying save user')
                )
                ->setData('')
                ->setError('User save error')
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
                        'user' => $request->getUser(),
                    ],
                ]
            );

            $this->response = (new Response())
                ->setStatus(
                    (new Status())
                        ->setCode($exception->getCode())
                        ->setMessage('Generic Error when trying register new user')
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
