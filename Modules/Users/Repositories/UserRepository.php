<?php

namespace Modules\Users\app\Repositories;

use App\Models\User;

/**
 * Database access layer for users
 */
class UserRepository
{
    public function all()
    {
        return User::with('roles')->latest()->get();
    }

    public function find(int $id)
    {
        return User::with('roles')->findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(int $id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);

        return $user;
    }

    public function delete(int $id): bool
    {
        return User::findOrFail($id)->delete();
    }
}