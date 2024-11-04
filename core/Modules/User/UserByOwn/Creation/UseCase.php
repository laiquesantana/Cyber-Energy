<?php

namespace Saas\Project\Modules\User\UserByOwn\Creation;

use Saas\Project\Dependencies\Interfaces\LogInterface;
use Saas\Project\Modules\User\Exceptions\UserSaveException;
use Saas\Project\Modules\User\Generics\Entities\Status;
use Saas\Project\Modules\User\Generics\Gateways\UserGateway;
use Saas\Project\Modules\User\UserByOwn\Creation\Builders\Builder;
use Saas\Project\Modules\User\UserByOwn\Creation\Requests\Request;
use Saas\Project\Modules\User\UserByOwn\Creation\Responses\Response;
use Saas\Project\Modules\User\UserByOwn\Creation\Responses\ResponseInterface;
use Saas\Project\Modules\User\UserByOwn\Creation\Rules\UserSaveRule;

class UseCase
{
    private UserGateway $userSaveGateway;

    private ResponseInterface $response;

    private const LOG_PREFIX = '[User/Creation::UseCase] ';

    private LogInterface $logger;

    public function __construct(
        UserGateway $userSaveGateway,
        LogInterface $logger

    ) {
        $this->userSaveGateway = $userSaveGateway;
        $this->logger = $logger;
    }

    public function execute(Request $request): void
    {
        try {
            $this->response = (new Builder())
                ->withUserSaveRule(
                    new UserSaveRule($this->userSaveGateway, $request->getUser())
                )
                ->build();
        } catch (UserSaveException $exception) {
            $this->logger->error(
                self::LOG_PREFIX.'User save exception error',
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
                        ->setCode(400)
                        ->setMessage('Error when trying save user')
                )
                ->setData([])
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
                        ->setCode(500)
                        ->setMessage('Generic Error when trying register new user')
                )
                ->setData([])
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
