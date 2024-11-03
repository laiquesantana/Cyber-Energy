<?php

namespace App\Http\Controllers\v1;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Saas\Project\Dependencies\Adapters\Logger\MonologLogAdapter;
use Saas\Project\Modules\User\Generics\Entities\User as UserEntity;
use Saas\Project\Modules\User\UserByOwn\Delete\Requests\Request as RequestDelete;
use Saas\Project\Modules\User\UserByOwn\Delete\UseCase as UserCaseDelete;
use Saas\Project\Modules\User\UserByOwn\Edit\Requests\Request as RequestUserEdit;
use Saas\Project\Modules\User\UserByOwn\Edit\UseCase;

class UserController extends BaseController
{
    use ApiResponser;

    public function edit(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'password' => [
                'sometimes',
                'string',
                'min:10',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'password_confirm' => 'required_with:password|same:password',
        ]);
        $useCase = new UseCase(new UserRepository(), new MonologLogAdapter());

        $oldUser = $request->user();

        $user = (new UserEntity())
            ->setFirstName($request->input('first_name') ?? $oldUser->first_name)
            ->setLastName($request->input('last_name') ?? $oldUser->last_name)
            ->setPassword($request->input('password') ?? $oldUser->password)
            ->setId($oldUser->id);

        $request = new RequestUserEdit($user);

        $useCase->execute($request);

        return response(
            $useCase->getResponse()->getPresenter()->toArray(),
            $useCase->getResponse()->getStatus()->getCode()
        );
    }

    public function delete(Request $request, $id)
    {
        try {
            $user = User::where('uuid', $id)->first();
            if (is_null($user)) {
                return $this->errorResponse('invalid user id');
            }
        } catch (\Throwable $exception) {
            return $this->errorResponse('Generic error occured');
        }
        $useCase = new UserCaseDelete(new UserRepository(), new MonologLogAdapter());

        $request = new RequestDelete($user->id);

        $useCase->execute($request);

        return response(
            $useCase->getResponse()->getPresenter()->toArray(),
            $useCase->getResponse()->getStatus()->getCode()
        );
    }
}
