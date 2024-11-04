<?php

namespace unit\Modules\User;


use PHPUnit\Framework\TestCase;
use Saas\Project\Dependencies\Interfaces\LogInterface;
use Saas\Project\Modules\User\Generics\Entities\Status;
use Saas\Project\Modules\User\Generics\Entities\User as EntitiesUser;
use Saas\Project\Modules\User\Generics\Gateways\UserGateway as GatewaysUserGateway;
use Saas\Project\Modules\User\UserByOwn\Creation\Requests\Request;
use Saas\Project\Modules\User\UserByOwn\Creation\Responses\Response;
use Saas\Project\Modules\User\UserByOwn\Creation\UseCase;

class UserTest extends TestCase
{
    public function testSuccess()
    {
        $userSaveGateway = $this->createMock(GatewaysUserGateway::class);
        $loggerMock = $this->createMock(LogInterface::class);

        $userSaveGateway->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(function ($user) {
                    $this->assertSame('firstName', $user->getFirstName());
                    $this->assertSame('lastName', $user->getLastName());
                    $this->assertSame('email@com', $user->getEmail());

                    return true;
                })
            );

        $useCase = new UseCase($userSaveGateway, $loggerMock);

        $user = new EntitiesUser();
        $user->setEmail('email@com');
        $user->setFirstName('firstName');
        $user->setLastName('lastName');

        $request = new Request($user);
        $useCase->execute($request);

        $this->assertEquals(201, $useCase->getResponse()->getStatus()->getCode());
    }

    public function testSaveUserError()
    {
        $userSaveGateway = $this->createMock(GatewaysUserGateway::class);
        $loggerMock = $this->createMock(LogInterface::class);

        $userSaveGateway->expects($this->once())
            ->method('save')
            ->willThrowException(new \Exception('Cannot save'));

        $useCase = new UseCase($userSaveGateway, $loggerMock);

        $user = new EntitiesUser();
        $user->setEmail('email@com');
        $user->setFirstName('firstName');
        $user->setLastName('lastName');

        $request = new Request($user);
        $useCase->execute($request);

        $expectedResponse = (new Response())
            ->setStatus(
                (new Status())
                    ->setCode(400)
                    ->setMessage('Error when trying save user')
            )
            ->setError('User save error')
            ->setMeta(['total' => 1])
            ->setData([]); // Adicionado para corresponder ao response atual

        $this->assertEquals($expectedResponse, $useCase->getResponse());
    }
}
