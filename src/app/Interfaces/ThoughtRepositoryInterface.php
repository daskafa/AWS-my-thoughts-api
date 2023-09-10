<?php

namespace App\Interfaces;

interface ThoughtRepositoryInterface
{
    public function getThoughts();

    public function createThought($request, $fileName): void;

    public function updateThought($thought, $request, $fileName): void;

    public function getThought(int $id);

    public function deleteThought($thought): void;
}
