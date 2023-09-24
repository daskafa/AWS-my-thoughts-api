<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\CommentRequest;
use App\Services\CommentService;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommentController extends Controller
{
    private CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(CommentRequest $request): JsonResponse
    {
        return $this->commentService->createComment($request);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->commentService->deleteComment($id);
    }
}
