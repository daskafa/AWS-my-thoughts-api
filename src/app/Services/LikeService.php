<?php

namespace App\Services;

use App\Constants\CommonConstants;
use App\Interfaces\LikeRepositoryInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class LikeService
{
    private LikeRepositoryInterface $likeRepository;

    public function __construct(LikeRepositoryInterface $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }

    public function likeDecider($request)
    {
        $id = $request->get('id');
        $userId = $request->get('user_id');
        $isLike = $request->get('is_like');
        $type = $request->get('type');

        $modelName = CommonConstants::LIKABLE_MODEL_NAMES[$type];

        try {
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

    private function makeDecision($isLike, $collection, $userId)
    {
        return match ((int)$isLike) {
            1 => $this->like($collection, $userId),
            0 => $this->unlike($collection, $userId),
            default => throw new Exception('Invalid like value.'),
        };
    }

    private function like($collection, $userId)
    {
        return $this->likeRepository->like($collection, $userId);
    }

    private function unlike($collection, $userId)
    {
        return $this->likeRepository->unlike($collection, $userId);
    }

    private function responseMessage($isLike)
    {
        return match ($isLike) {
            'liked' => 'Successfully liked.',
            'unliked' => 'Successfully unliked.',
            default => throw new Exception('Invalid like value.'),
        };
    }
}
