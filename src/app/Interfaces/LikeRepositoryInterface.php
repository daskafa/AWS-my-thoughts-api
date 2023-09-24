<?php

namespace App\Interfaces;

use App\Models\Comment;
use App\Models\Thought;

interface LikeRepositoryInterface
{
    public function getCollection(string $modelName, int $id): Comment|Thought|null;

    public function like(Comment|Thought $collection, int $userId): string;

    public function unlike(Comment|Thought $collection, int $userId): string;

    public function isLiked(Comment|Thought $collection, int $userId): bool;
}
