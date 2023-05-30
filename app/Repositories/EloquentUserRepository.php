<?php

namespace App\Repositories;

use App\Business\User\Domain\User;
use App\Business\User\Port\UserRepository;
use App\Models\User as UserModel;

class EloquentUserRepository implements UserRepository {

    public function create(User $user): User {
        $model = new UserModel([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password
        ]);
        $model->save();
        $model->refresh();
        return $user->copyWith(id: $model->id);
    }

    public function find(int $id): User {
        $model = UserModel::findOrFail($id);
        return User::buildExistentUser($model->id, $model->name, $model->email, $model->password);
    }

    public function searchByEmail(string $email): User|null {
        $model = UserModel::whereEmail($email);
        return $model ? User::buildExistentUser(
            $model->id, $model->name, $model->email, $model->password
        ) : null;
    }

    public function update(User $user): User {
        $model = UserModel::findOrFail($user->id);
        $model->name = $user->name;
        $model->email = $user->email;
        $model->save();
        return $user;
    }
}