<?php

namespace App\Repositories;

use App\Interfaces\LikeRepositoryInterface;

class LikeRepository implements LikeRepositoryInterface
{
    public function getCollection($modelName, $id)
    {
        return $modelName::find($id);
    }

    public function like($collection, $userId)
    {
        $collection->likes()->create([
            'user_id' => $userId
        ]);

        return 'liked';
    }

    public function unlike($collection, $userId)
    {
        $collection->likes()->where('user_id', $userId)->delete();

        return 'unliked';
    }

    public function isLiked($collection, $userId)
    {
        return $collection->likes()->where('user_id', $userId)->exists();
    }
}
