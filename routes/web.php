<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BucketController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [BucketController::class, 'home'])->name('home');
Route::get('/cost-explorer', [BucketController::class, 'costExplorer'])->name('cost-explorer');
Route::get('/object-explorer', [BucketController::class, 'objectExplorer'])->name('object-explorer');
Route::get('/object-downloader', [BucketController::class, 'objectDownloader'])->name('object-downloader');
Route::get('/api/bucket-details', [BucketController::class, 'getBucketDetails']);

