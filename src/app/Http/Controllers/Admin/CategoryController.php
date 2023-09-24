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
        return $this->categoryService->createCategory($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->categoryService->getCategory($id);
    }

    public function update(CategoryRequest $request, int $id): JsonResponse
    {
        return $this->categoryService->updateCategory($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->categoryService->deleteCategory($id);
    }
}
