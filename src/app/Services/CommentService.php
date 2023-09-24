<?php

namespace App\Services;

use App\Constants\CommonConstants;
use App\Interfaces\CommentRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CommentService
{
    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function createComment(Request $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        $validatedRequest['user_id'] = auth()->id();

        try {
            $this->commentRepository->createComment($validatedRequest);

            return responseJson(
                type: 'message',
                message: 'Comment created successfully.',
                status: Response::HTTP_CREATED
            );
        } catch (Exception $exception) {
            return exceptionResponseJson(
                message: CommonConstants::GENERAL_EXCEPTION_ERROR_MESSAGE,
                exceptionMessage: $exception->getMessage()
            );
        }
    }

    public function deleteComment(int $id): JsonResponse
    {
        try {
            $comment = $this->commentRepository->getComment($id);

            if (is_null($comment)) {
                return responseJson(
                    type: 'message',
                    message: 'Comment not found.',
                    status: Response::HTTP_NOT_FOUND
                );
            }

            $this->commentRepository->deleteComment($comment);

            return responseJson(
                type: 'message',
                message: 'Comment deleted successfully.',
                status: Response::HTTP_OK
            );
        } catch (Exception $exception) {
            return exceptionResponseJson(
                message: CommonConstants::GENERAL_EXCEPTION_ERROR_MESSAGE,
                exceptionMessage: $exception->getMessage()
            );
        }
    }
}
