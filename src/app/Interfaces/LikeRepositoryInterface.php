<?php

namespace App\Interfaces;

interface LikeRepositoryInterface
{
    public function getCollection($modelName, $id);

    public function like($collection, $userId);

    public function unlike($collection, $userId);

    public function isLiked($collection, $userId);
}
