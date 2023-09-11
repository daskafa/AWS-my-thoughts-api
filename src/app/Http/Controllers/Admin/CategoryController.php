<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return $this->categoryService->getCategories();
    }

    public function store(CategoryRequest $request)
    {
        return $this->categoryService->createCategory($request);
    }

    public function show(string $id)
    {
        return $this->categoryService->getCategory($id);
    }

    public function update(CategoryRequest $request, string $id)
    {
        return $this->categoryService->updateCategory($request, $id);
    }

    public function destroy(string $id)
    {
        return $this->categoryService->deleteCategory($id);
    }
}
