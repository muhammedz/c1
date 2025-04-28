<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileManagerSystem\FilemanagersystemController;
use App\Http\Controllers\FileManagerSystem\FilemanagersystemMediaController;
use App\Http\Controllers\FileManagerSystem\FilemanagersystemFolderController;
use App\Http\Controllers\FileManagerSystem\FilemanagersystemCategoryController;
use App\Http\Controllers\FileManagerSystem\FilemanagersystemRelationController;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\ApiTokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Token Routes
Route::post('/token', [ApiTokenController::class, 'createToken']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tokens', [ApiTokenController::class, 'tokens']);
    Route::delete('/tokens', [ApiTokenController::class, 'revokeTokens']);
    Route::delete('/tokens/{id}', [ApiTokenController::class, 'revokeToken']);
});

// News API Routes - API Key ile korumalı
Route::middleware('api.key')->prefix('news')->group(function () {
    Route::get('/', [NewsApiController::class, 'index']);
    Route::get('/featured', [NewsApiController::class, 'getFeatured']);
    Route::get('/categories', [NewsApiController::class, 'getCategories']);
    Route::get('/category/{category_id}', [NewsApiController::class, 'getByCategory']);
    Route::get('/{slug}', [NewsApiController::class, 'show']);
});

// File Manager System API Routes
Route::prefix('filemanagersystem')->group(function () {
    // Media Routes
    Route::get('media', [FilemanagersystemMediaController::class, 'index']);
    Route::post('media', [FilemanagersystemMediaController::class, 'store']);
    Route::get('media/{media}', [FilemanagersystemMediaController::class, 'show']);
    Route::put('media/{media}', [FilemanagersystemMediaController::class, 'update']);
    Route::delete('media/{media}', [FilemanagersystemMediaController::class, 'destroy']);
    Route::get('media/{media}/download', [FilemanagersystemMediaController::class, 'download']);

    // Folder Routes
    Route::get('folders', [FilemanagersystemFolderController::class, 'index']);
    Route::post('folders', [FilemanagersystemFolderController::class, 'store']);
    Route::get('folders/{folder}', [FilemanagersystemFolderController::class, 'show']);
    Route::put('folders/{folder}', [FilemanagersystemFolderController::class, 'update']);
    Route::delete('folders/{folder}', [FilemanagersystemFolderController::class, 'destroy']);

    // Category Routes
    Route::get('categories', [FilemanagersystemCategoryController::class, 'index']);
    Route::post('categories', [FilemanagersystemCategoryController::class, 'store']);
    Route::get('categories/{category}', [FilemanagersystemCategoryController::class, 'show']);
    Route::put('categories/{category}', [FilemanagersystemCategoryController::class, 'update']);
    Route::delete('categories/{category}', [FilemanagersystemCategoryController::class, 'destroy']);

    // Relation Routes
    Route::get('relations', [FilemanagersystemRelationController::class, 'index']);
    Route::post('relations', [FilemanagersystemRelationController::class, 'store']);
    Route::put('relations/{relation}', [FilemanagersystemRelationController::class, 'update']);
    Route::delete('relations/{relation}', [FilemanagersystemRelationController::class, 'destroy']);
    Route::post('relations/reorder', [FilemanagersystemRelationController::class, 'reorder']);

    // Search and Bulk Actions
    Route::post('bulk-actions', [FilemanagersystemController::class, 'bulkActions']);
});
