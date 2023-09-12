<?php

namespace App\Repositories;

use App\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;

class CommentRepository implements CommentRepositoryInterface
{
    public function createComment($request): void
    {
        Comment::create($request);
    }

    public function getComment(int $id)
    {
        return Comment::find($id);
    }

    public function deleteComment($comment): void
    {
        $comment->delete();
    }
}
