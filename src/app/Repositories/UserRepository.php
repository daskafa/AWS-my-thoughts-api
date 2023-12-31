<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function createUser(array $request): void
    {
        User::create($request);
    }

    public function getAuthUserRole(): string
    {
        return auth()->user()->role;
    }
}
