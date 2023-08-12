<?php
// Files routes

use App\Services\Filerepo\Controllers\FilesController;
use Illuminate\Support\Facades\Route;

$controller = FilesController::class;


$middleWares[] = 'auth:sanctum';

// Prefix all generated routes
$prefix = 'api/admin';
// Middlewares to be passed before accessing any route
$middleWares = ['api', 'nested_routes_auth'];
$middleWares[] = 'auth:sanctum';
Route::middleware(array_filter(array_merge($middleWares, [])))
    ->prefix($prefix)
    ->group(function () use ($controller) {
        Route::post('file-repo/tmp', [$controller, 'storeFile']);
        Route::post('file-repo/tmp/delete/{id}', [$controller, 'destroyFile']);
        Route::post('file-repo/add-files', [$controller, 'addImages']);
        Route::post('file-repo/image/delete/{id}', [$controller, 'deleteImage']);
        Route::post('file-repo/upload-image', [$controller, 'uploadImage']);
        Route::get('file-repo/{path}', [$controller, 'show'])->where('path', '.*')->name('file.show');
    });
