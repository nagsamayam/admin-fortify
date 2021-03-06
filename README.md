# Backend authentication for admin

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nagsamayam/admin-fortify.svg?style=flat-square)](https://packagist.org/packages/nagsamayam/admin-fortify)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/nagsamayam/admin-fortify/run-tests?label=tests)](https://github.com/nagsamayam/admin-fortify/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/nagsamayam/admin-fortify/Check%20&%20fix%20styling?label=code%20style)](https://github.com/nagsamayam/admin-fortify/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/nagsamayam/admin-fortify.svg?style=flat-square)](https://packagist.org/packages/nagsamayam/admin-fortify)

---
This repo can be used as admin Fortify.
---

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.


## Installation

You can install the package via composer:

Add below to your composer.json `repositories` array

```bash
{
    "type": "vcs",
    "url": "https://github.com/nagsamayam/admin-fortify"
}
```

```bash
composer require nagsamayam/admin-fortify
```

The package will automatically register itself.

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="NagSamayam\AdminFortify\AdminFortifyServiceProvider" --tag="admin-fortify-migrations"
php artisan migrate
```

You can publish the DB seeder file with:
```bash
php artisan vendor:publish --provider="NagSamayam\AdminFortify\AdminFortifyServiceProvider" --tag="admin-fortify-seeders"
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="NagSamayam\AdminFortify\AdminFortifyServiceProvider" --tag="admin-fortify-config"
```


## Usage

Add below to your config/auth.php `guards` array

```bash
    'admin' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],
```

Add below to your config/auth.php `providers` array

```bash
    'admins' => [
        'driver' => 'eloquent',
        'model' => NagSamayam\AdminFortify\Models\Admin::class,
    ],
```

Create new file in app/Providers/AdminFortifyServiceProvider.php and add below content. Make sure view files exists

```bash
    <?php

    namespace App\Providers;

    use Illuminate\Http\Request;
    use NagSamayam\AdminFortify\Fortify;
    use Illuminate\Support\ServiceProvider;
    use Illuminate\Cache\RateLimiting\Limit;
    use Illuminate\Support\Facades\RateLimiter;

    class AdminFortifyServiceProvider extends ServiceProvider
    {
        /**
        * Register any application services.
        *
        * @return void
        */
        public function register()
        {
            
        }

        /**
        * Bootstrap any application services.
        *
        * @return void
        */
        public function boot()
        {

            RateLimiter::for('admin-login', function (Request $request) {
                return Limit::perMinute(3)->by($request->email . $request->ip())->response(function () {
                    return back()->withErrors(['email' => 'Too many login attempts.',]);
                });
            });

            RateLimiter::for('admin-two-factor', function (Request $request) {
                return Limit::perMinute(3)->by($request->session()->get('login.id'));
            });

            Fortify::loginView(fn () => view('admin.auth.login'));

            Fortify::twoFactorChallengeView(function () {
                return view('admin.auth.two-factor-challenge');
            });
        }
    }
```

```bash
php artisan migrate
php artisan db:seed --class=SuperAdminSeeder
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Nageswara Rao](https://github.com/nagsamayam)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
