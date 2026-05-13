<?php

use App\Http\Controllers\Api\Auth\AuthenticatedUserController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\V1\LogoutController;
use App\Http\Controllers\Api\V1\PublicBlogPostController;
use App\Http\Controllers\Api\V1\PublicEventController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::get('/health', HealthController::class)->name('api.health');

Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show'])
    ->middleware('web')
    ->name('api.sanctum.csrf-cookie');

Route::middleware('auth:sanctum')->get('/user', [AuthenticatedUserController::class, 'show'])
    ->name('api.user.show');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth:sanctum', 'signed', 'throttle:6,1'])
    ->name('api.verify-email');

Route::prefix('v1')->group(function () {
    Route::get('/events', [PublicEventController::class, 'index'])
        ->middleware('throttle:60,1')
        ->name('api.v1.events.index');

    Route::get('/news', [PublicBlogPostController::class, 'index'])
        ->middleware('throttle:60,1')
        ->name('api.v1.news.index');

    Route::get('/news/{slug}', [PublicBlogPostController::class, 'show'])
        ->middleware('throttle:60,1')
        ->name('api.v1.news.show');

    Route::get('/blog', [PublicBlogPostController::class, 'index'])
        ->middleware('throttle:60,1')
        ->name('api.v1.blog.index');

    Route::get('/blog/{slug}', [PublicBlogPostController::class, 'show'])
        ->middleware('throttle:60,1')
        ->name('api.v1.blog.show');
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::post('/logout', LogoutController::class)
        ->name('api.v1.logout');
});
