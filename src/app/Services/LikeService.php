<?php

namespace App\Services;

use App\Constants\CommonConstants;
use App\Interfaces\LikeRepositoryInterface;
use App\Models\Comment;
use App\Models\Thought;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LikeService
{
    private LikeRepositoryInterface $likeRepository;
    private array $likeDeciderData;

    public function __construct(LikeRepositoryInterface $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }

    public function likeDecider(): JsonResponse
    {
        $id = $this->likeDeciderData['id'];
        $userId = $this->likeDeciderData['userId'];
        $isLike = $this->likeDeciderData['isLike'];
        $type = $this->likeDeciderData['type'];

        try {
            $modelName = CommonConstants::LIKABLE_MODEL_NAMES[$type];
            $collection = $this->likeRepository->getCollection($modelName, $id);

            if (is_null($collection)) {
                return responseJson(
                    type: 'message',
                    message: 'Resource not found.',
                    status: Response::HTTP_NOT_FOUND
                );
            }

            $checkIfLiked = $this->likeRepository->isLiked($collection, $userId);

            if ($checkIfLiked && $isLike) {
                return responseJson(
                    type: 'message',
                    message: 'You already liked this.',
                    status: Response::HTTP_BAD_REQUEST
                );
            }

            $likedState = $this->makeDecision($isLike, $collection, $userId);
            $responseMessage = $this->responseMessage($likedState);

            return responseJson(
                type: 'message',
                message: $responseMessage,
                status: Response::HTTP_OK
            );
        } catch (Exception $exception) {
            return exceptionResponseJson(
                message: CommonConstants::GENERAL_EXCEPTION_ERROR_MESSAGE,
                exceptionMessage: $exception->getMessage()
            );
        }
    }

    /**
     * @throws Exception
     */
    private function makeDecision(bool $isLike, Comment|Thought $collection, int $userId): string|Exception
    {
        return match ($isLike) {
            true => $this->like($collection, $userId),
            false => $this->unlike($collection, $userId),
            default => throw new Exception('Invalid like value.'),
        };
    }

    private function like(Comment|Thought $collection, int $userId): string
    {
        return $this->likeRepository->like($collection, $userId);
    }

    private function unlike(Comment|Thought $collection, int $userId): string
    {
        return $this->likeRepository->unlike($collection, $userId);
    }

    /**
     * @throws Exception
     */
    private function responseMessage(string $likedState): string|Exception
    {
        return match ($likedState) {
            'liked' => 'Successfully liked.',
            'unliked' => 'Successfully unliked.',
            default => throw new Exception('Invalid like value.'),
        };
    }

    public function setLikeDeciderData(int $userId, int $id, int $isLike, string $type): void
    {
        $this->likeDeciderData = [
            'userId' => $userId,
            'id' => $id,
            'isLike' => $isLike,
            'type' => $type,
        ];
    }
}
