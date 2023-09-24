<?php

namespace App\Interfaces;

use App\Models\Comment;

interface CommentRepositoryInterface
{
    public function getComment(int $id): Comment|null;

    public function createComment(array $request): void;

    public function deleteComment(Comment $comment): bool;
}
