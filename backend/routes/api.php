<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // --- Public auth & no-OTP verification (rate limited) ---
    Route::middleware('throttle:auth')->group(function () {
        Route::post('/auth/staff/login', [AuthController::class, 'staffLogin']);
        Route::post('/auth/resident/login', [AuthController::class, 'residentLogin']);
        Route::post('/auth/platform/login', [AuthController::class, 'platformLogin']);
    });

    Route::middleware('throttle:verification')->group(function () {
        Route::get('/verification/societies', [VerificationController::class, 'societies']);
        Route::get('/verification/societies/{society}/units', [VerificationController::class, 'units']);
        Route::post('/verification/register', [VerificationController::class, 'register']);
    });

    // --- Authenticated (any guard: society users, residents, platform admins) ---
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
    });

    // --- Society users only (staff + residents, not platform admins) ---
    Route::middleware(['auth:sanctum', 'society_user'])->group(function () {
        Route::post('/verification/link-unit', [VerificationController::class, 'linkAdditionalUnit']);

        // Module route groups (billing, complaints, SOS, ...) are added here
        // as each module is implemented - see PROGRESS.md.
    });

    // --- Platform admin only ---
    Route::middleware(['auth:sanctum', 'platform_admin'])->group(function () {
        // Society onboarding, subscriptions, platform ads, global reports
        // are added here as the Platform Admin module is implemented.
    });
});
