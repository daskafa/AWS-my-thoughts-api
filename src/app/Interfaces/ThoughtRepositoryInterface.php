<?php

namespace App\Interfaces;

use App\Models\Thought;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ThoughtRepositoryInterface
{
    public function getThoughts(): Collection;

    public function createThought(Request $request, string|null $fileName): void;

    public function updateThought(Thought $thought, Request $request, string $fileName): void;

    public function getThought(int $id): Thought|null;

    public function deleteThought(Thought $thought): bool;
}
