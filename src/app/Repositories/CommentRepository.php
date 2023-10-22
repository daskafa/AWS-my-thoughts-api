<?php

namespace App\Repositories;

use App\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;

class CommentRepository implements CommentRepositoryInterface
{
    public function createComment(array $request): void
    {
        Comment::create($request);
    }

    public function getComment(int $id): Comment|null
    {
        return Comment::find($id);
    }

    public function deleteComment(Comment $comment): bool
    {
        return $comment->delete();
    }
}
