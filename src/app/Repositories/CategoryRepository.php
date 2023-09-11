<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getCategories()
    {
        return Category::orderBy('created_at', 'desc')->get();
    }

    public function createCategory($request, $fileName): void
    {
        Category::create([
            'title' => $request->get('title'),
            'banner' => $fileName
        ]);
    }

    public function updateCategory($category, $request, $fileName): void
    {
        $category->update([
            'title' => $request->get('title'),
            'banner' => $fileName
        ]);
    }

    public function getCategory(int $id)
    {
        return Category::find($id);
    }

    public function deleteCategory($category): bool
    {
        return $category->delete();
    }
}
