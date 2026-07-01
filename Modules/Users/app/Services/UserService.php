<?php

namespace Modules\Users\app\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Repositories\UserRepository;

/**
 * Business logic layer for Users
 */
class UserService
{
    public function __construct(
        private UserRepository $repo
    ) {}

    public function list()
    {
        return $this->repo->all();
    }

    public function find(int $id)
    {
        return $this->repo->find($id);
    }

    public function createView()
    {
        return [];
    }

    public function editView(int $id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);

        $user = $this->repo->create($data);

        if (!empty($data['role'])) {
            $user->assignRole($data['role']);
        }

        return $user;
    }

    public function update(int $id, array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user = $this->repo->update($id, $data);

        if (!empty($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        return $user;
    }

    public function delete(int $id)
    {
        return $this->repo->delete($id);
    }
}