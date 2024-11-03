<?php

namespace App\Repositories;

use App\Models\User as UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Saas\Project\Modules\User\Generics\Entities\User;
use Saas\Project\Modules\User\Generics\Gateways\UserGateway;

class UserRepository implements UserGateway
{
    public function save(User $userEntity): User
    {
        $user = UserModel::create([
            'email' => $userEntity->getEmail(),
            'first_name' => $userEntity->getFirstName(),
            'last_name' => $userEntity->getLastName(),
            'password' => password_hash($userEntity->getPassword(), PASSWORD_ARGON2I),
        ]);

        $user->refresh();

        $userEntity->setUuid($user->uuid);

        return $userEntity;
    }

    public function update(User $userEntity): User
    {
        $user = UserModel::findOrFail($userEntity->getId());

        $user->update([
            'first_name' => $userEntity->getFirstName(),
            'last_name' => $userEntity->getLastName(),
        ]);
        if ($userEntity->getPassword() != '') {
            $user->password = hash::make($userEntity->getPassword());
            $user->save();
        }

        return (new User())
            ->setUuid($user->uuid)
            ->setFirstName($user->first_name)
            ->setLastName($user->last_name)
            ->setEmail($user->email);
    }

    public function delete(int $id): bool
    {
        $user = Auth::user();
        activity()
            ->causedBy($user)
            ->log('deleted');

        $user = UserModel::findOrFail($id);

        return $user->delete();
    }
}
