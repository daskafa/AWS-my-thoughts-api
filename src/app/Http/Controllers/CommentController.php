<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\CommentRequest;
use App\Services\CommentService;

class CommentController extends Controller
{
    private CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(CommentRequest $request)
    {
        return $this->commentService->createComment($request);
    }

    public function destroy(string $id)
    {
        return $this->commentService->deleteComment($id);
    }
}
