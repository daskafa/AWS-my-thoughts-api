<?php

namespace App\Services;

use App\Constants\CommonConstants;
use App\Exceptions\ValidationException;
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
        [$id, $userId, $isLike, $type] = $this->likeDeciderExtracted();

        try {
            $collection = $this->checkAndRetrieveCollection($type, $id, $userId, $isLike);
            $likedState = $this->makeDecision($isLike, $collection, $userId);
            $responseMessage = $this->responseMessage($likedState);

            return responseJson(
                type: 'message',
                message: $responseMessage,
                status: Response::HTTP_OK
            );
        } catch (Exception $exception) {
            if ($exception instanceof ValidationException) {
                return responseJson(
                    type: 'message',
                    message: $exception->getMessage(),
                    status: $exception->getCode()
                );
            }

            return exceptionResponseJson(
                message: CommonConstants::GENERAL_EXCEPTION_ERROR_MESSAGE,
                exceptionMessage: $exception->getMessage()
            );
        }
    }

    /**
     * @throws Exception
     */
    private function makeDecision(bool $isLike, Comment|Thought $collection, int $userId): string
    {
        return match ($isLike) {
            true => $this->like($collection, $userId),
            false => $this->unlike($collection, $userId),
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
    private function responseMessage(string $likedState): string
    {
        return match ($likedState) {
            'liked' => 'Successfully liked.',
            'unliked' => 'Successfully unliked.',
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

    public function likeDeciderExtracted(): array
    {
        $id = $this->likeDeciderData['id'];
        $userId = $this->likeDeciderData['userId'];
        $isLike = $this->likeDeciderData['isLike'];
        $type = $this->likeDeciderData['type'];

        return array($id, $userId, $isLike, $type);
    }

    private function checkAndRetrieveCollection(string $type, int $id, int $userId, int $isLike): Comment|Thought
    {
        $modelName = CommonConstants::LIKABLE_MODEL_NAMES[$type];

        $collection = $this->likeRepository->getCollection($modelName, $id);

        if (is_null($collection)) {
            throw new ValidationException('Resource not found.', Response::HTTP_NOT_FOUND);
        }

        $checkIfLiked = $this->likeRepository->isLiked($collection, $userId);

        if ($checkIfLiked && $isLike) {
            throw new ValidationException('You already liked this.', Response::HTTP_BAD_REQUEST);
        }

        return $collection;
    }
}
