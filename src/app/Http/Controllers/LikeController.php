<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\LikeRequest;
use App\Services\LikeService;
use Symfony\Component\HttpFoundation\JsonResponse;

class LikeController extends Controller
{
    private LikeService $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    public function likeDecider(LikeRequest $request): JsonResponse
    {
        $this->prepareAndSetLikeDeciderData($request);

        return $this->likeService->likeDecider();
    }

    private function prepareAndSetLikeDeciderData(LikeRequest $request): void
    {
        $this->likeService->setLikeDeciderData(
            auth()->id(),
            $request->get('id'),
            $request->get('is_like'),
            $request->get('type')
        );
    }
}
