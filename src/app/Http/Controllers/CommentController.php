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
        $this->prepareAndSetStoreData($request);

        return $this->commentService->createComment();
    }

    public function destroy(int $id): JsonResponse
    {
        $this->prepareAndSetDestroyData($id);

        return $this->commentService->deleteComment();
    }

    private function prepareAndSetStoreData(CommentRequest $request): void
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        $this->commentService->setStoreData($validated);
    }

    private function prepareAndSetDestroyData(int $id): void
    {
        $this->commentService->setDestroyData($id);
    }
}
