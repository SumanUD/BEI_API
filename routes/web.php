<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AboutUsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Brands
    Route::get('/brands/index', [BrandController::class, 'index'])->name('brands.index');
    Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');

    // About Us
    Route::get('/about', [AboutUsController::class, 'index'])->name('about.index');
    Route::get('/about/create', [AboutUsController::class, 'create'])->name('about.create');
    Route::post('/about', [AboutUsController::class, 'store'])->name('about.store');
    Route::get('/about/{about}/edit', [AboutUsController::class, 'edit'])->name('about.edit');
    Route::put('/about/{about}', [AboutUsController::class, 'update'])->name('about.update');
});
