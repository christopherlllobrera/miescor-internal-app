<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DownloadableActivityController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\WorkflowController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/blog', [PostController::class, 'index'])->name('posts.index');
Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/home/data-privacy-consent', function () {
    return view('filament.app.DataPrivacyConsent');
});

Route::get('/under-development', function () {
    return view('under_development');
});

Route::get('/department/{slug}', [DepartmentController::class, 'show'])->name('department.show');
Route::get('/downloadable-modules/{id}/download', [DepartmentController::class, 'download'])
    ->name('downloadable-modules.download');
Route::get('/departmentpage', function () {
    return view('departmentpage');
});

Route::get('/faqpage', function () {
    return view('faqpage');
});

// FAQ Routes (nested under department)
Route::get('/department/{department}/faq', [FAQController::class, 'index'])->name('faq.index');
Route::get('/department/{department}/faq/{slug}', [FAQController::class, 'show'])->name('faq.show');

// Workflow Routes (nested under department)
Route::get('/department/{department}/workflow', [WorkflowController::class, 'index'])->name('workflow.index');
Route::get('/department/{department}/workflow/{slug}', [WorkflowController::class, 'show'])->name('workflow.show');

// Log downloadable clicks (used by frontend JS)
Route::post('/downloadable/log', [DownloadableActivityController::class, 'log'])->name('downloadable.log');
