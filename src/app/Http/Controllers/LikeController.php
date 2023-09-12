<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\LikeRequest;
use App\Services\LikeService;

class LikeController extends Controller
{
    private LikeService $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    public function likeDecider(LikeRequest $request)
    {
        return $this->likeService->likeDecider($request);
    }
}
