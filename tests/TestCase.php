<?php

namespace NagSamayam\AdminFortify\Tests;

use CreateAdminsTable;
use Illuminate\Database\Eloquent\Factories\Factory;
use NagSamayam\AdminFortify\AdminFortifyServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'NagSamayam\\AdminFortify\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            AdminFortifyServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        include_once __DIR__ . '/../database/migrations/create_admins_table.php.stub';
        (new CreateAdminsTable())->up();
    }
}
