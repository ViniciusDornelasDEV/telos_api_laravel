<?php

namespace Modules\User\Services;

use Modules\User\Repositories\UserRepository;
use Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepository $repository
    ) {}

    public function list()
    {
        return $this->repository->all();
    }

    public function insert(array $data): User
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'type'     => $data['type'],
            'status'   => $data['status'],
        ]);
        return $user;
    }

    public function update(User $user, array $data): User
    {
        $payload = [
            'name'   => $data['name'],
            'email'  => $data['email'],
            'type'   => $data['type'],
            'status' => $data['status'],
        ];
        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }
        $user->update($payload);
        
        return $user->fresh();
    }
}
