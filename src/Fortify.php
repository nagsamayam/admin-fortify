<?php

namespace NagSamayam\AdminFortify;

use NagSamayam\AdminFortify\Contracts\ConfirmPasswordViewResponse;
use NagSamayam\AdminFortify\Contracts\LoginViewResponse;
use NagSamayam\AdminFortify\Contracts\TwoFactorChallengeViewResponse;
use NagSamayam\AdminFortify\Contracts\UpdatesUserPasswords;
use NagSamayam\AdminFortify\Contracts\UpdatesUserProfileInformation;
use NagSamayam\AdminFortify\Http\Responses\SimpleViewResponse;

class Fortify
{
    /**
     * The callback that is responsible for building the authentication pipeline array, if applicable.
     *
     * @var callable|null
     */
    public static $authenticateThroughCallback;

    /**
     * The callback that is responsible for validating authentication credentials, if applicable.
     *
     * @var callable|null
     */
    public static $authenticateUsingCallback;

    /**
     * The callback that is responsible for confirming user passwords.
     *
     * @var callable|null
     */
    public static $confirmPasswordsUsingCallback;

    /**
     * Indicates if Fortify routes will be registered.
     *
     * @var bool
     */
    public static $registersRoutes = true;

    /**
     * Get the username used for authentication.
     *
     * @return string
     */
    public static function username()
    {
        return config('admin_fortify.username', 'email');
    }

    /**
     * Get the name of the email address request variable / field.
     *
     * @return string
     */
    public static function email()
    {
        return config('admin_fortify.email', 'email');
    }

    /**
     * Get a completion redirect path for a specific feature.
     *
     * @param  string  $redirect
     * @return string
     */
    public static function redirects(string $redirect, $default = null)
    {
        return config('admin_fortify.redirects.' . $redirect) ?? $default ?? config('admin_fortify.home');
    }

    /**
     * Register the views for Fortify using conventional names under the given namespace.
     *
     * @param  string  $namespace
     * @return void
     */
    public static function viewNamespace(string $namespace)
    {
        return static::viewPrefix($namespace . '::');
    }

    /**
     * Register the views for Fortify using conventional names under the given prefix.
     *
     * @param  string  $prefix
     * @return void
     */
    public static function viewPrefix(string $prefix)
    {
        static::loginView($prefix . 'login');
        static::twoFactorChallengeView($prefix . 'two-factor-challenge');
        static::confirmPasswordView($prefix . 'confirm-password');
    }

    /**
     * Specify which view should be used as the login view.
     *
     * @param  callable|string  $view
     * @return void
     */
    public static function loginView($view)
    {
        app()->singleton(LoginViewResponse::class, function () use ($view) {
            return new SimpleViewResponse($view);
        });
    }

    /**
     * Specify which view should be used as the two factor authentication challenge view.
     *
     * @param  callable|string  $view
     * @return void
     */
    public static function twoFactorChallengeView($view)
    {
        app()->singleton(TwoFactorChallengeViewResponse::class, function () use ($view) {
            return new SimpleViewResponse($view);
        });
    }

    /**
     * Specify which view should be used as the password confirmation prompt.
     *
     * @param  callable|string  $view
     * @return void
     */
    public static function confirmPasswordView($view)
    {
        app()->singleton(ConfirmPasswordViewResponse::class, function () use ($view) {
            return new SimpleViewResponse($view);
        });
    }

    /**
     * Register a callback that is responsible for building the authentication pipeline array.
     *
     * @param  callable  $callback
     * @return void
     */
    public static function loginThrough(callable $callback)
    {
        return static::authenticateThrough($callback);
    }

    /**
     * Register a callback that is responsible for building the authentication pipeline array.
     *
     * @param  callable  $callback
     * @return void
     */
    public static function authenticateThrough(callable $callback)
    {
        static::$authenticateThroughCallback = $callback;
    }

    /**
     * Register a callback that is responsible for validating incoming authentication credentials.
     *
     * @param  callable  $callback
     * @return void
     */
    public static function authenticateUsing(callable $callback)
    {
        static::$authenticateUsingCallback = $callback;
    }

    /**
     * Register a callback that is responsible for confirming existing user passwords as valid.
     *
     * @param  callable  $callback
     * @return void
     */
    public static function confirmPasswordsUsing(callable $callback)
    {
        static::$confirmPasswordsUsingCallback = $callback;
    }

    /**
     * Register a class / callback that should be used to update user profile information.
     *
     * @param  string  $callback
     * @return void
     */
    public static function updateUserProfileInformationUsing(string $callback)
    {
        return app()->singleton(UpdatesUserProfileInformation::class, $callback);
    }

    /**
     * Register a class / callback that should be used to update user passwords.
     *
     * @param  string  $callback
     * @return void
     */
    public static function updateUserPasswordsUsing(string $callback)
    {
        return app()->singleton(UpdatesUserPasswords::class, $callback);
    }

    /**
     * Configure Fortify to not register its routes.
     *
     * @return static
     */
    public static function ignoreRoutes()
    {
        static::$registersRoutes = false;

        return new static();
    }
}
