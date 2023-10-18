<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends Controller
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse
    {
        return $this->categoryService->getCategories();
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $this->prepareAndSetStoreData($request);
        return $this->categoryService->createCategory();
    }

    public function show(int $id): JsonResponse
    {
        return $this->categoryService->getCategory($id);
    }

    public function update(CategoryRequest $request, int $id): JsonResponse
    {
        $this->prepareAndSetUpdateData($request);
        return $this->categoryService->updateCategory($id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->categoryService->deleteCategory($id);
    }

    private function prepareAndSetStoreData(CategoryRequest $request): void
    {
        $this->categoryService->setStoreData(
            $request->get('title'),
            $request->file('banner')
        );
    }

    private function prepareAndSetUpdateData(CategoryRequest $request): void
    {
        $this->categoryService->setUpdateData(
            $request->get('title'),
            $request->file('banner')
        );
    }
}
