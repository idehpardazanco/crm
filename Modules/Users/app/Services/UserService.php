<?php

namespace Modules\Users\app\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Users\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private readonly UserRepository $repository
    ) {
    }

    public function paginate(
        ?string $search = null
    ): LengthAwarePaginator {
        return $this->repository->paginate(
            $search
        );
    }

    public function find(int $id): User
    {
        return $this->repository->find($id);
    }

    public function create(array $data): User
    {
        $role = $data['role'];

        unset($data['role']);

        $user = $this->repository->create(
            $data
        );

        $user->syncRoles([
            $role,
        ]);

        return $user->load('roles');
    }

    public function update(
        int $id,
        array $data
    ): User {
        $role = $data['role'];

        unset($data['role']);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user = $this->repository->update(
            $id,
            $data
        );

        $user->syncRoles([
            $role,
        ]);

        return $user->load('roles');
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete(
            $id
        );
    }
}