<?php

namespace App\Services;

use App\Constants\CommonConstants;
use App\Interfaces\CategoryRepositoryInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class CategoryService
{
    private CategoryRepositoryInterface $categoryRepository;
    private FileService $fileService;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        FileService $fileService
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->fileService = $fileService;
    }

    public function getCategories()
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

    public function createCategory($request)
    {
        try {
            if ($request->hasFile('banner')) {
                $fileName = $this->fileService->s3Upload(file: $request->file('banner'), path: CommonConstants::CATEGORY_BANNER_S3_BASE_PATH);
            }

            $this->categoryRepository->createCategory($request, $fileName ?? null);

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

    public function getCategory(string $id)
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

    public function updateCategory($request, string $id)
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
            if ($request->hasFile('banner')) {
                $fileName = $this->fileService->s3Upload(file: $request->file('banner'), path: CommonConstants::CATEGORY_BANNER_S3_BASE_PATH);
            }

            if (isset($fileName)) {
                $this->fileService->s3Delete(fileName: $category->banner, path: CommonConstants::CATEGORY_BANNER_S3_BASE_PATH);
            }

            $this->categoryRepository->updateCategory($category, $request, $fileName ?? $category->banner);

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

    public function deleteCategory(string $id)
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
            $category->delete();

            $this->fileService->s3Delete(fileName: $category->banner, path: CommonConstants::CATEGORY_BANNER_S3_BASE_PATH);

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
}
