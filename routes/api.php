<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BrandController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ServicePageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ContactMessageController;


Route::get('news', [NewsController::class, 'allNews']);
Route::get('/allbrands', [BrandController::class, 'jsonIndex']);
Route::get('/homecms', [HomePageController::class, 'jsonHomePage']);
Route::get('/aboutcms', [AboutUsController::class, 'jsonAboutUs']);
Route::get('/servicescms', [ServicePageController::class, 'jsonServicePage']);
Route::post('/contact', [ContactMessageController::class, 'store']);
