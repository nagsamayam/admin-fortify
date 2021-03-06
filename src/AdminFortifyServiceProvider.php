<?php

namespace NagSamayam\AdminFortify;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use NagSamayam\AdminFortify\Actions\AttemptToAuthenticate;
use NagSamayam\AdminFortify\Actions\RedirectIfTwoFactorAuthenticatable;
use NagSamayam\AdminFortify\Commands\AdminFortifyCommand;
use NagSamayam\AdminFortify\Contracts\FailedPasswordConfirmationResponse as FailedPasswordConfirmationResponseContract;
use NagSamayam\AdminFortify\Contracts\FailedTwoFactorLoginResponse as FailedTwoFactorLoginResponseContract;
use NagSamayam\AdminFortify\Contracts\LockoutResponse as LockoutResponseContract;
use NagSamayam\AdminFortify\Contracts\LoginResponse as LoginResponseContract;
use NagSamayam\AdminFortify\Contracts\LogoutResponse as LogoutResponseContract;
use NagSamayam\AdminFortify\Contracts\PasswordConfirmedResponse as PasswordConfirmedResponseContract;
use NagSamayam\AdminFortify\Contracts\TwoFactorAuthenticationProvider as TwoFactorAuthenticationProviderContract;
use NagSamayam\AdminFortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use NagSamayam\AdminFortify\Http\Controllers\AuthenticatedSessionController;
use NagSamayam\AdminFortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use NagSamayam\AdminFortify\Http\Middleware\AdminAuthenticate;
use NagSamayam\AdminFortify\Http\Middleware\RedirectIfAdminAuthenticated;
use NagSamayam\AdminFortify\Http\Responses\FailedPasswordConfirmationResponse;
use NagSamayam\AdminFortify\Http\Responses\FailedTwoFactorLoginResponse;
use NagSamayam\AdminFortify\Http\Responses\LockoutResponse;
use NagSamayam\AdminFortify\Http\Responses\LoginResponse;
use NagSamayam\AdminFortify\Http\Responses\LogoutResponse;
use NagSamayam\AdminFortify\Http\Responses\PasswordConfirmedResponse;
use NagSamayam\AdminFortify\Http\Responses\TwoFactorLoginResponse;
use NagSamayam\AdminFortify\Providers\EventServiceProvider;
use ReflectionClass;

class AdminFortifyServiceProvider extends ServiceProvider
{
    public string $packageName;

    public string $basePath;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->packageName = 'admin-fortify';
        $this->setBasePath($this->getPackageBaseDir());

        $this->mergeConfigFrom(__DIR__ . '/../config/fortify.php', 'admin_fortify');

        $this->registerResponseBindings();

        $this->app->singleton(
            TwoFactorAuthenticationProviderContract::class,
            TwoFactorAuthenticationProvider::class
        );

        $this->app->when([
            AuthenticatedSessionController::class,
            AttemptToAuthenticate::class,
            RedirectIfTwoFactorAuthenticatable::class,
            TwoFactorAuthenticatedSessionController::class,
        ])
            ->needs(StatefulGuard::class)
            ->give(function () {
                return Auth::guard(config('admin_fortify.guard', null));
            });


        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register the response bindings.
     *
     * @return void
     */
    protected function registerResponseBindings()
    {
        $this->app->singleton(FailedPasswordConfirmationResponseContract::class, FailedPasswordConfirmationResponse::class);
        $this->app->singleton(FailedTwoFactorLoginResponseContract::class, FailedTwoFactorLoginResponse::class);
        $this->app->singleton(LockoutResponseContract::class, LockoutResponse::class);
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(TwoFactorLoginResponseContract::class, TwoFactorLoginResponse::class);
        $this->app->singleton(LogoutResponseContract::class, LogoutResponse::class);
        $this->app->singleton(PasswordConfirmedResponseContract::class, PasswordConfirmedResponse::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            AdminFortifyCommand::class,
        ]);
        $this->configurePublishing();
        $this->configureRoutes();
        $this->configureMiddlewares();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'adminFortify');
    }

    /**
     * Configure the publishable resources offered by the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/fortify.php' => config_path('admin_fortify.php'),
        ], 'admin-fortify-config');

        $migrationFileNames = [
            'create_admins_table',
            'create_admin_login_log_table',
        ];

        $now = now();
        foreach ($migrationFileNames as $migrationFileName) {
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    $this->basePath("/../database/migrations/{$migrationFileName}.php.stub") => with($migrationFileName, function ($migrationFileName) use ($now) {
                        $migrationPath = 'migrations/';

                        if (Str::contains($migrationFileName, '/')) {
                            $migrationPath .= Str::of($migrationFileName)->beforeLast('/')->finish('/');
                            $migrationFileName = Str::of($migrationFileName)->afterLast('/');
                        }

                        return database_path($migrationPath . $now->addSecond()->format('Y_m_d_His') . '_' . Str::of($migrationFileName)->snake()->finish('.php'));
                    }),
                ], "{$this->shortName()}-migrations");
            }
        }

        if (! class_exists('SuperAdminSeeder')) {
            $this->publishes([
                __DIR__ . '/../database/seeders/SuperAdminSeeder.php.stub' => database_path('seeders/SuperAdminSeeder.php'),
            ], "{$this->shortName()}-seeders");
        }
    }

    /**
     * Configure the routes offered by the application.
     *
     * @return void
     */
    protected function configureRoutes()
    {
        if (Fortify::$registersRoutes) {
            Route::group([
                'namespace' => 'NagSamayam\AdminFortify\Http\Controllers',
                'domain' => config('admin_fortify.domain', null),
                'prefix' => config('admin_fortify.prefix', 'admin'),
            ], function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
            });
        }
    }

    protected function configureMiddlewares()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('auth.admin', AdminAuthenticate::class);
        $router->aliasMiddleware('admin', RedirectIfAdminAuthenticated::class);
    }

    public function basePath(string $directory = null): string
    {
        if ($directory === null) {
            return $this->basePath;
        }

        return $this->basePath . DIRECTORY_SEPARATOR . ltrim($directory, DIRECTORY_SEPARATOR);
    }

    public function setBasePath(string $path)
    {
        $this->basePath = $path;
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $migrationsPath = 'migrations/';

        $len = strlen($migrationFileName) + 4;

        if (Str::contains($migrationFileName, '/')) {
            $migrationsPath .= Str::of($migrationFileName)->beforeLast('/')->finish('/');
            $migrationFileName = Str::of($migrationFileName)->afterLast('/');
        }

        foreach (glob(database_path("${migrationsPath}*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName . '.php')) {
                return true;
            }
        }

        return false;
    }

    public function shortName(): string
    {
        return Str::after($this->packageName, 'laravel-');
    }

    protected function getPackageBaseDir(): string
    {
        $reflector = new ReflectionClass(get_class($this));

        return dirname($reflector->getFileName());
    }
}
