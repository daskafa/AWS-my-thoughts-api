<?php

namespace App\Services;

use App\Constants\CommonConstants;
use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CategoryService
{
    private CategoryRepositoryInterface $categoryRepository;
    private FileService $fileService;
    private array $storeData;
    private array $updateData;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        FileService $fileService
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->fileService = $fileService;
    }

    public function getCategories(): JsonResponse
    {
        $categories = $this->categoryRepository->getCategories();

        if ($categories->isEmpty()) {
            return responseJson(
                type: 'message',
                message: 'No categories found.',
                status: Response::HTTP_NOT_FOUND
            );
        }

        return responseJson(
            type: 'data',
            data: $categories,
        );
    }

    public function createCategory(): JsonResponse
    {
        try {
            $fileName = $this->fileService->s3Upload(file: $this->storeData['banner'], path: CommonConstants::CATEGORY_BANNER_S3_BASE_PATH);

            $this->categoryRepository->createCategory($this->storeData, $fileName ?? null);

            return responseJson(
                type: 'message',
                message: 'Category created successfully.',
                status: Response::HTTP_CREATED
            );
        } catch (Exception $exception) {
            return exceptionResponseJson(
                message: CommonConstants::GENERAL_EXCEPTION_ERROR_MESSAGE,
                exceptionMessage: $exception->getMessage()
            );
        }
    }

    public function getCategory(int $id): JsonResponse
    {
        $category = $this->categoryRepository->getCategory($id);

        if (is_null($category)) {
            return responseJson(
                type: 'message',
                message: 'Category not found.',
                status: Response::HTTP_NOT_FOUND
            );
        }

        return responseJson(
            type: 'data',
            data: $category,
        );
    }

    public function updateCategory(int $id): JsonResponse
    {
        $category = $this->categoryRepository->getCategory($id);

        if (is_null($category)) {
            return responseJson(
                type: 'message',
                message: 'Category not found.',
                status: Response::HTTP_NOT_FOUND
            );
        }

        try {
            $fileName = $this->fileService->s3Upload(file: $this->updateData['banner'], path: CommonConstants::CATEGORY_BANNER_S3_BASE_PATH);
            $this->fileService->s3Delete(fileName: $category->banner, path: CommonConstants::CATEGORY_BANNER_S3_BASE_PATH);

            $this->categoryRepository->updateCategory($category, $this->updateData, $fileName);

            return responseJson(
                type: 'message',
                message: 'Category updated successfully.',
                status: Response::HTTP_OK
            );
        } catch (Exception $exception) {
            return exceptionResponseJson(
                message: CommonConstants::GENERAL_EXCEPTION_ERROR_MESSAGE,
                exceptionMessage: $exception->getMessage()
            );
        }
    }

    public function deleteCategory(int $id): JsonResponse
    {
        $category = $this->categoryRepository->getCategory($id);

        if (is_null($category)) {
            return responseJson(
                type: 'message',
                message: 'Category not found.',
                status: Response::HTTP_NOT_FOUND
            );
        }

        try {
            $this->deleteCategoryAndThoughtFiles($category);
            $this->categoryRepository->deleteCategory($category);

            return responseJson(
                type: 'message',
                message: 'Category deleted successfully.',
                status: Response::HTTP_OK
            );
        } catch (Exception $exception) {
            return exceptionResponseJson(
                message: CommonConstants::GENERAL_EXCEPTION_ERROR_MESSAGE,
                exceptionMessage: $exception->getMessage()
            );
        }
    }

    private function deleteCategoryAndThoughtFiles(Category $category): void
    {
        $this->fileService->s3Delete(
            fileName: $category->banner,
            path: CommonConstants::CATEGORY_BANNER_S3_BASE_PATH
        );

        $this->fileService->s3DeleteMultiple(
            fileNames: $category->thoughts->pluck('photo')->toArray(),
            path: CommonConstants::THOUGHT_PHOTO_S3_BASE_PATH
        );
    }

    public function setStoreData(string $title, object $banner): void
    {
        $this->storeData = [
            'title' => $title,
            'banner' => $banner,
        ];
    }

    public function setUpdateData(string $title, object $banner): void
    {
        $this->updateData = [
            'title' => $title,
            'banner' => $banner,
        ];
    }
}
