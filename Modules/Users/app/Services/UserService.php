<?php

namespace Modules\Users\app\Services;

use Illuminate\Support\Facades\Hash;
use Modules\Monitoring\app\Services\MonitoringService;
use Modules\Users\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly MonitoringService $monitoringService
    ) {
    }

    public function paginate(
        ?string $search = null
    ) {
        return $this->repository
            ->paginate($search);
    }

    public function find(
        int $id
    ) {
        return $this->repository
            ->find($id);
    }

    public function create(
        array $data
    ) {
        $role =
            $data['role']
            ?? null;

        unset(
            $data['role']
        );

        $data['password'] =
            Hash::make(
                $data['password']
            );

        $user =
            $this->repository
                ->create($data);

        if ($role) {
            $user->assignRole(
                $role
            );
        }

        $this
            ->monitoringService
            ->activity(
                'user_created',
                'Users',
                [
                    'target_user_id' =>
                        $user->id,

                    'name' =>
                        $user->name,

                    'mobile' =>
                        $user->mobile,

                    'role' =>
                        $role,
                ]
            );

        return $user;
    }

    public function update(
        int $id,
        array $data
    ) {
        $user =
            $this->repository
                ->find($id);

        $role =
            $data['role']
            ?? null;

        unset(
            $data['role']
        );

        if (
            ! empty(
                $data['password']
            )
        ) {
            $data['password'] =
                Hash::make(
                    $data['password']
                );
        } else {
            unset(
                $data['password']
            );
        }

        $oldStatus =
            $user->status;

        $oldRoles =
            $user
                ->roles
                ->pluck('name')
                ->values()
                ->all();

        $user =
            $this->repository
                ->update(
                    $id,
                    $data
                );

        if ($role) {
            $user->syncRoles([
                $role,
            ]);
        }

        $this
            ->monitoringService
            ->activity(
                'user_updated',
                'Users',
                [
                    'target_user_id' =>
                        $user->id,

                    'old_status' =>
                        $oldStatus,

                    'new_status' =>
                        $user->status,

                    'old_roles' =>
                        $oldRoles,

                    'new_role' =>
                        $role,
                ]
            );

        return $user;
    }

    public function delete(
        int $id
    ): bool {
        $user =
            $this->repository
                ->find($id);

        $meta = [
            'target_user_id' =>
                $user->id,

            'name' =>
                $user->name,

            'mobile' =>
                $user->mobile,
        ];

        $deleted =
            $this->repository
                ->delete($id);

        if ($deleted) {
            $this
                ->monitoringService
                ->activity(
                    'user_deleted',
                    'Users',
                    $meta
                );
        }

        return $deleted;
    }
}