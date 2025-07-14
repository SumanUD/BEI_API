<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\ServicePageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\WebsiteLoaderController;

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

    // About Us CMS
    Route::get('/about-us', [AboutUsController::class, 'index'])->name('about.index');
    Route::post('/about-us', [AboutUsController::class, 'storeOrUpdate'])->name('about.save');
    Route::get('/team-member-template', [AboutUsController::class, 'teamMemberTemplate'])->name('admin.team-member-template');

    // Home Page CMS
    Route::get('/home-page', [HomePageController::class, 'form'])->name('homepag.form');
    Route::post('/home-page/save', [HomePageController::class, 'save'])->name('homepag.save');

    // Services
    Route::get('/services', [ServicePageController::class, 'index'])->name('services.index');
    Route::post('/services', [ServicePageController::class, 'storeOrUpdate'])->name('services.storeOrUpdate');

    // News 
    Route::resource('news', NewsController::class);

    // Contact
    Route::get('/admin/contact-messages', [ContactMessageController::class, 'index'])->name('admin.contact.index');
    Route::get('/admin/contact-messages/data', [ContactMessageController::class, 'getData'])->name('admin.contact.data');
    Route::get('/admin/contact-messages/{id}', [ContactMessageController::class, 'show'])->name('admin.contact.show');
    Route::delete('/admin/contact-messages/{id}', [ContactMessageController::class, 'destroy'])->name('admin.contact.destroy');


    Route::get('/admin/website-loader', [WebsiteLoaderController::class, 'edit'])->name('website-loader.edit');
    Route::post('/admin/website-loader', [WebsiteLoaderController::class, 'update'])->name('website-loader.update');

});
