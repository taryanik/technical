<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function register(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        $user->refresh();

        return $user;
    }
}
