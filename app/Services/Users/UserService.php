<?php

namespace App\Services\Users;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Response;
use Psr\Log\LoggerInterface;
use Saas\Project\Dependencies\Interfaces\LogInterface;

class UserService
{
    private UserRepository $userRepository;
    private LogInterface $logger;
    private Response $response;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function editUser(User $user)
    {
        try {
            $this->userRepository->update($user);
            $this->response = new Response('User Updated Successfully', 200);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->response = new Response('Error updating user', 500);
        }
    }

    public function getResponse()
    {
        return $this->response;
    }
}