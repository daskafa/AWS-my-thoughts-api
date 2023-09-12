<?php

namespace App\Interfaces;

interface CommentRepositoryInterface
{
    public function getComment(int $id);

    public function createComment($request): void;

    public function deleteComment(string $id);
}
