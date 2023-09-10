<?php


use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'categories' => CategoryController::class,
]);
