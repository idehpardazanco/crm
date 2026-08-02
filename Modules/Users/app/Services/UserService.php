<?php

namespace Modules\Users\app\Services;


use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Repositories\UserRepository;



class UserService
{

    public function __construct(
        protected UserRepository $repository
    )
    {

    }



    public function paginate(?string $search = null)
    {

        return $this->repository->paginate($search);

    }





    public function create(array $data)
    {

        $password = $data['password'];

        unset($data['role']);

        $data['password'] = Hash::make($password);


        $user = User::create($data);


        $user->assignRole(
            request()->role
        );


        return $user;

    }





    public function update(
        int $id,
        array $data
    )
    {

        $user = User::findOrFail($id);


        $role = $data['role'] ?? null;


        unset($data['role']);


        if(
            !empty($data['password'])
        ){

            $data['password'] =
                Hash::make($data['password']);

        }
        else {

            unset($data['password']);

        }



        $user->update($data);



        if($role){

            $user->syncRoles([
                $role
            ]);

        }



        return $user;

    }

    public function delete(int $id)
    {

        return User::findOrFail($id)
            ->delete();

    }


}