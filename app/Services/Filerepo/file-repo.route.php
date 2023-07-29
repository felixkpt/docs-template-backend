<?php
//Files routes

use App\Services\Filerepo\Controllers\FilesController;
use Illuminate\Support\Facades\Route;

$controller = FilesController::class;

Route::middleware('web')->group(function () use ($controller) {
    Route::post('admin/file-repo/tmp', [$controller, 'storeFile']);
    Route::post('admin/file-repo/tmp/delete/{id}', [$controller, 'destroyFile']);
    Route::post('admin/file-repo/add-files', [$controller, 'addImages']);
    Route::post('admin/file-repo/image/delete/{id}', [$controller, 'deleteImage']);
    Route::post('admin/file-repo/upload-image', [$controller, 'uploadImage']);
});
