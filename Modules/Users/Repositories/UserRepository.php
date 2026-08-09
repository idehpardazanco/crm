<?php

namespace Modules\Users\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function paginate(
        ?string $search = null
    ): LengthAwarePaginator {
        return User::query()
            ->with('roles')
            ->when(
                $search,
                function ($query, string $search) {
                    $query->where(
                        function ($query) use ($search) {
                            $query
                                ->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'mobile',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'email',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }

    public function find(int $id): User
    {
        return User::query()
            ->with('roles')
            ->findOrFail($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(
        int $id,
        array $data
    ): User {
        $user = User::findOrFail($id);

        $user->update($data);

        return $user->refresh();
    }

    public function delete(int $id): bool
    {
        return (bool) User::findOrFail($id)
            ->delete();
    }
}