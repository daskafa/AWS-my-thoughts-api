<?php

namespace App\Interfaces;

interface CategoryRepositoryInterface
{
    public function getCategories();

    public function createCategory($request, $fileName): void;

    public function updateCategory($category, $request, $fileName): void;

    public function getCategory(int $id);

    public function deleteCategory($category): bool;
}
