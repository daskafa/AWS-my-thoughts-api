<?php

namespace App\Services;

use App\Constants\CommonConstants;
use App\Interfaces\ThoughtRepositoryInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ThoughtService
{
    private ThoughtRepositoryInterface $thoughtRepository;
    private FileService $fileService;

    public function __construct(
        ThoughtRepositoryInterface $thoughtRepository,
        FileService $fileService
    ) {
        $this->thoughtRepository = $thoughtRepository;
        $this->fileService = $fileService;
    }

    public function getThoughts()
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

    public function createThought($request)
    {
        try {
            if ($request->hasFile('photo')) {
                $fileName = $this->fileService->s3Upload(file: $request->file('photo'), path: CommonConstants::THOUGHT_PHOTO_S3_BASE_PATH);
            }

            $this->thoughtRepository->createThought($request, $fileName ?? null);

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

    public function getThought($id)
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

    public function updateThought($request, $id)
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

            if ($request->hasFile('photo')) {
                $fileName = $this->fileService->s3Upload(file: $request->file('photo'), path: CommonConstants::THOUGHT_PHOTO_S3_BASE_PATH);
            }

            if (isset($fileName)) {
                $this->fileService->s3Delete(fileName: $thought->photo, path: CommonConstants::THOUGHT_PHOTO_S3_BASE_PATH);
            }

            $this->thoughtRepository->updateThought($thought, $request, $fileName ?? $thought->photo);

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

    public function deleteThought($id)
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
}
