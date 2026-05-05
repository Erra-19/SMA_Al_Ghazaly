<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AlbumController;
use App\Http\Controllers\Api\AlumniController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\Admin;
use Illuminate\Support\Facades\Route;

// ─── Webhook Midtrans (no auth, no CSRF) ─────────────────────────────────────
Route::post('webhooks/midtrans', [WebhookController::class, 'midtrans']);

// ─── Auth ─────────────────────────────────────────────────────────────────────
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me', [AuthController::class, 'me']);
});

// ─── Public endpoints ─────────────────────────────────────────────────────────
Route::get('settings', [SettingController::class, 'index']);
Route::get('pages', [PageController::class, 'index']);
Route::get('pages/{slug}', [PageController::class, 'show']);

Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{slug}', [PostController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);

Route::get('teachers', [TeacherController::class, 'index']);
Route::get('testimonials', [TestimonialController::class, 'index']);
Route::get('alumni', [AlumniController::class, 'index']);

Route::get('albums', [AlbumController::class, 'index']);
Route::get('albums/{slug}', [AlbumController::class, 'show']);

Route::get('forms/{slug}', [FormController::class, 'show']);
Route::post('forms/{slug}/submit', [FormController::class, 'submit']);

// PPDB — publik
Route::post('registrations', [RegistrationController::class, 'store']);
Route::post('registrations/{id}/documents', [RegistrationController::class, 'uploadDocuments']);
Route::get('registrations/{number}/status', [RegistrationController::class, 'checkStatus']);

// ─── Admin endpoints (auth + sanctum) ────────────────────────────────────────
Route::middleware('auth:sanctum')->prefix('admin')->name('admin.')->group(function () {

    Route::get('dashboard', [Admin\DashboardController::class, 'index']);

    Route::apiResource('posts', Admin\PostController::class);
    Route::apiResource('categories', Admin\CategoryController::class)->except('show');
    Route::apiResource('pages', Admin\PageController::class);
    Route::apiResource('teachers', Admin\TeacherController::class);
    Route::apiResource('albums', Admin\AlbumController::class);
    Route::apiResource('testimonials', Admin\TestimonialController::class)->except('show');
    Route::apiResource('alumni', Admin\AlumniController::class)->except('show');

    Route::post('medias', [Admin\MediaController::class, 'store']);
    Route::get('medias', [Admin\MediaController::class, 'index']);
    Route::delete('medias/{id}', [Admin\MediaController::class, 'destroy']);

    Route::get('registrations', [Admin\RegistrationController::class, 'index']);
    Route::get('registrations/{id}', [Admin\RegistrationController::class, 'show']);
    Route::patch('registrations/{id}/status', [Admin\RegistrationController::class, 'updateStatus']);

    Route::get('payments', [Admin\PaymentController::class, 'index']);
    Route::get('payments/{id}', [Admin\PaymentController::class, 'show']);

    Route::get('messages', [Admin\FormSubmissionController::class, 'index']);
    Route::get('messages/{id}', [Admin\FormSubmissionController::class, 'show']);
    Route::delete('messages/{id}', [Admin\FormSubmissionController::class, 'destroy']);

    Route::apiResource('users', Admin\UserController::class);
    Route::get('roles', [Admin\UserController::class, 'roles']);

    Route::get('settings', [Admin\SettingController::class, 'index']);
    Route::put('settings', [Admin\SettingController::class, 'update']);
});
