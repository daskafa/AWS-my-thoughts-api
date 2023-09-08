<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function createUser($request): void;
}
