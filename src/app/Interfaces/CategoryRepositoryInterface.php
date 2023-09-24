<?php

namespace App\Interfaces;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    public function getCategories(): Collection;

    public function createCategory(Request $request, string|null $fileName): void;

    public function updateCategory(Category $category, Request $request, string $fileName): void;

    public function getCategory(int $id): Category|null;

    public function deleteCategory(Category $category): bool;
}
