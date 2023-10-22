<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getCategories(): Collection
    {
        return Category::orderBy('created_at', 'desc')->get();
    }

    public function createCategory(array $request, string|null $fileName): void
    {
        Category::create([
            'title' => $request['title'],
            'banner' => $fileName
        ]);
    }

    public function updateCategory(Category $category, array $request, string $fileName): void
    {
        $category->update([
            'title' => $request['title'],
            'banner' => $fileName
        ]);
    }

    public function getCategory(int $id): Category|null
    {
        return Category::find($id);
    }

    public function deleteCategory(Category $category): bool
    {
        return $category->delete();
    }
}
