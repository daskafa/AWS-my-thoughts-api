<?php

namespace App\Services;

use App\Constants\CommonConstants;
use App\Interfaces\ThoughtRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ThoughtService
{
    private ThoughtRepositoryInterface $thoughtRepository;
    private FileService $fileService;
    private array $storeData;
    private array $updateData;

    public function __construct(
        ThoughtRepositoryInterface $thoughtRepository,
        FileService $fileService
    ) {
        $this->thoughtRepository = $thoughtRepository;
        $this->fileService = $fileService;
    }

    public function getThoughts(): JsonResponse
    {
        $thoughts = $this->thoughtRepository->getThoughts();

        if ($thoughts->isEmpty()) {
            return responseJson(
                type: 'message',
                message: 'No thoughts found.',
                status: Response::HTTP_NOT_FOUND
            );
        }

        return responseJson(
            type: 'data',
            data: $thoughts,
        );
    }

    public function createThought(): JsonResponse
    {
        try {
            $fileName = $this->fileService->s3Upload(file: $this->storeData['photo'], path: CommonConstants::THOUGHT_PHOTO_S3_BASE_PATH);

            $this->thoughtRepository->createThought($this->storeData, $fileName ?? null);

            return responseJson(
                type: 'message',
                message: 'Thought created successfully.',
                status: Response::HTTP_CREATED
            );
        } catch (Exception $exception) {
            return exceptionResponseJson(
                message: CommonConstants::GENERAL_EXCEPTION_ERROR_MESSAGE,
                exceptionMessage: $exception->getMessage()
            );
        }
    }

    public function getThought(int $id): JsonResponse
    {
        $thought = $this->thoughtRepository->getThought($id);

        if (is_null($thought)) {
            return responseJson(
                type: 'message',
                message: 'Thought not found.',
                status: Response::HTTP_NOT_FOUND
            );
        }

        return responseJson(
            type: 'data',
            data: $thought,
        );
    }

    public function updateThought(int $id): JsonResponse
    {
        try {
            $thought = $this->thoughtRepository->getThought($id);

            if (is_null($thought)) {
                return responseJson(
                    type: 'message',
                    message: 'Thought not found.',
                    status: Response::HTTP_NOT_FOUND
                );
            }

            if (!is_null($this->updateData['photo'])) {
                $fileName = $this->fileService->s3Upload(file: $this->updateData['photo'], path: CommonConstants::THOUGHT_PHOTO_S3_BASE_PATH);
            }

            if (isset($fileName)) {
                $this->fileService->s3Delete(fileName: $thought->photo, path: CommonConstants::THOUGHT_PHOTO_S3_BASE_PATH);
            }

            $this->thoughtRepository->updateThought($thought, $this->updateData, $fileName ?? $thought->photo);

            return responseJson(
                type: 'message',
                message: 'Thought updated successfully.',
                status: Response::HTTP_OK
            );
        } catch (Exception $exception) {
            return exceptionResponseJson(
                message: CommonConstants::GENERAL_EXCEPTION_ERROR_MESSAGE,
                exceptionMessage: $exception->getMessage()
            );
        }
    }

    public function deleteThought(int $id): JsonResponse
    {
        try {
            $thought = $this->thoughtRepository->getThought($id);

            if (is_null($thought)) {
                return responseJson(
                    type: 'message',
                    message: 'Thought not found.',
                    status: Response::HTTP_NOT_FOUND
                );
            }

            $this->thoughtRepository->deleteThought($thought);

            $this->fileService->s3Delete(fileName: $thought->photo, path: CommonConstants::THOUGHT_PHOTO_S3_BASE_PATH);

            return responseJson(
                type: 'message',
                message: 'Thought deleted successfully.',
                status: Response::HTTP_OK
            );
        } catch (Exception $exception) {
            return exceptionResponseJson(
                message: CommonConstants::GENERAL_EXCEPTION_ERROR_MESSAGE,
                exceptionMessage: $exception->getMessage()
            );
        }
    }

    public function setStoreData(int $categoryId, object $photo, string $title, string $content, int $type): void
    {
        $this->storeData = [
            'category_id' => $categoryId,
            'photo' => $photo,
            'title' => $title,
            'content' => $content,
            'type' => $type,
        ];
    }

    public function setUpdateData(int $categoryId, object|null $photo, string $title, string $content, int $type): void
    {
        $this->updateData = [
            'category_id' => $categoryId,
            'photo' => $photo,
            'title' => $title,
            'content' => $content,
            'type' => $type,
        ];
    }
}
