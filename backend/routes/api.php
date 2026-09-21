<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\VerificationController;
use App\Http\Controllers\Api\V1\Platform\AdCampaignController;
use App\Http\Controllers\Api\V1\Platform\AdvertiserController;
use App\Http\Controllers\Api\V1\Platform\PlatformReportController;
use App\Http\Controllers\Api\V1\Platform\SocietyController;
use App\Http\Controllers\Api\V1\Platform\SocietySubscriptionController;
use App\Http\Controllers\Api\V1\Platform\SubscriptionPlanController;
use App\Http\Controllers\Api\V1\Units\BlockController;
use App\Http\Controllers\Api\V1\Units\StreetController;
use App\Http\Controllers\Api\V1\Units\TariffTypeController;
use App\Http\Controllers\Api\V1\Units\UnitCategoryController;
use App\Http\Controllers\Api\V1\Units\UnitController;
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

        // --- Units & Property (units.manage for writes, units.view for reads) ---
        Route::middleware('permission:units.view')->group(function () {
            Route::get('/units', [UnitController::class, 'index']);
            Route::get('/units/{unit}', [UnitController::class, 'show']);
            Route::get('/blocks', [BlockController::class, 'index']);
            Route::get('/streets', [StreetController::class, 'index']);
            Route::get('/unit-categories', [UnitCategoryController::class, 'index']);
            Route::get('/tariff-types', [TariffTypeController::class, 'index']);
        });

        Route::middleware('permission:units.manage')->group(function () {
            Route::post('/units', [UnitController::class, 'store']);
            Route::put('/units/{unit}', [UnitController::class, 'update']);
            Route::delete('/units/{unit}', [UnitController::class, 'destroy']);
            Route::post('/units/{unit}/release', [UnitController::class, 'releaseUnit']);

            Route::post('/blocks', [BlockController::class, 'store']);
            Route::put('/blocks/{id}', [BlockController::class, 'update']);
            Route::delete('/blocks/{id}', [BlockController::class, 'destroy']);

            Route::post('/streets', [StreetController::class, 'store']);
            Route::put('/streets/{id}', [StreetController::class, 'update']);
            Route::delete('/streets/{id}', [StreetController::class, 'destroy']);

            Route::post('/unit-categories', [UnitCategoryController::class, 'store']);
            Route::put('/unit-categories/{id}', [UnitCategoryController::class, 'update']);
            Route::delete('/unit-categories/{id}', [UnitCategoryController::class, 'destroy']);

            Route::post('/tariff-types', [TariffTypeController::class, 'store']);
            Route::put('/tariff-types/{id}', [TariffTypeController::class, 'update']);
            Route::delete('/tariff-types/{id}', [TariffTypeController::class, 'destroy']);
        });

        Route::middleware('permission:units.import')->post('/units/import', [UnitController::class, 'import']);

        // Further module route groups (billing, complaints, SOS, ...) are
        // added here as each module is implemented - see PROGRESS.md.
    });

    // --- Platform admin only ---
    Route::middleware(['auth:sanctum', 'platform_admin'])->group(function () {
        Route::get('/platform/dashboard', [PlatformReportController::class, 'dashboard']);
        Route::get('/platform/audit-log', [PlatformReportController::class, 'auditLog']);

        Route::apiResource('platform/societies', SocietyController::class)->parameters(['societies' => 'society'])->except(['destroy']);
        Route::post('/platform/societies/{society}/suspend', [SocietyController::class, 'suspend']);
        Route::post('/platform/societies/{society}/activate', [SocietyController::class, 'activate']);

        Route::get('/platform/societies/{society}/subscriptions', [SocietySubscriptionController::class, 'index']);
        Route::post('/platform/societies/{society}/subscriptions', [SocietySubscriptionController::class, 'store']);
        Route::post('/platform/societies/{society}/subscriptions/{subscription}/cancel', [SocietySubscriptionController::class, 'cancel']);

        Route::apiResource('platform/subscription-plans', SubscriptionPlanController::class)->except(['show']);
        Route::apiResource('platform/advertisers', AdvertiserController::class)->except(['show']);
        Route::apiResource('platform/ad-campaigns', AdCampaignController::class)->except(['show']);
    });
});
