<?php

namespace App\Repositories;

use App\Interfaces\LikeRepositoryInterface;
use App\Models\Comment;
use App\Models\Thought;

class LikeRepository implements LikeRepositoryInterface
{
    public function getCollection(string $modelName, int $id): Comment|Thought|null
    {
        return $modelName::find($id);
    }

    public function like(Comment|Thought $collection, int $userId): string
    {
        $collection->likes()->create([
            'user_id' => $userId
        ]);

        return 'liked';
    }

    public function unlike(Comment|Thought $collection, int $userId): string
    {
        $collection->likes()->where('user_id', $userId)->delete();

        return 'unliked';
    }

    public function isLiked(Comment|Thought $collection, int $userId): bool
    {
        return $collection->likes()->where('user_id', $userId)->exists();
    }
}
