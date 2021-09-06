<?php

use Illuminate\Support\Facades\Route;
use NagSamayam\AdminFortify\Features;
use NagSamayam\AdminFortify\Http\Controllers\ProfileInformationController;
use NagSamayam\AdminFortify\Http\Controllers\AuthenticatedSessionController;
use NagSamayam\AdminFortify\Http\Controllers\TwoFactorAuthenticationController;
use NagSamayam\AdminFortify\Http\Controllers\TwoFactorAuthenticatedSessionController;

Route::group(['middleware' => config('admin_fortify.middleware', ['web',])], function () {
    $enableViews = config('admin_fortify.views', true);

    // Authentication...
    if ($enableViews) {
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])
            ->middleware(['admin:' . config('admin_fortify.guard'),])
            ->name('admin.login');
    }

    $limiter = config('admin_fortify.limiters.login');
    $twoFactorLimiter = config('admin_fortify.limiters.two-factor');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'admin:' . config('admin_fortify.guard'),
            $limiter ? 'throttle:' . $limiter : null,
        ]));

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth.admin')
        ->name('admin.logout');

    // Profile Information...
    if (Features::enabled(Features::updateProfileInformation())) {
        Route::put('/profile-information', [ProfileInformationController::class, 'update'])
            ->middleware(['auth.admin'])
            ->name('admin.profile-information.update');
    }


    // Two Factor Authentication...
    if (Features::enabled(Features::twoFactorAuthentication())) {
        if ($enableViews) {
            Route::get('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'create'])
                ->middleware(['admin:' . config('admin_fortify.guard')])
                ->name('admin.two-factor.login');
        }

        Route::post('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'store'])
            ->middleware(array_filter([
                'admin:' . config('admin_fortify.guard'),
                $twoFactorLimiter ? 'throttle:' . $twoFactorLimiter : null,
            ]));

        $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
            ? ['auth.admin:' . config('admin_fortify.guard'), 'password.confirm']
            : ['auth.admin:' . config('admin_fortify.guard')];

        Route::post('/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])
            ->middleware($twoFactorMiddleware);

        Route::delete('/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])
            ->middleware($twoFactorMiddleware);
    }
});
