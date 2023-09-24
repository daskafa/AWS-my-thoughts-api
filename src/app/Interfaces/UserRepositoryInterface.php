<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function createUser(array $request): void;

    public function getAuthUserRole(): string;
}
