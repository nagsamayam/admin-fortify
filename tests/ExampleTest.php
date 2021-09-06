<?php

namespace NagSamayam\AdminFortify\Tests;


class ExampleTest extends TestCase
{
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }

    /** @test **/
    public function it_does_a_certain_thing()
    {
        // Application::starting(function ($artisan) {
        //     $artisan->add(app(AdminFortifyCommand::class));
        // });


        // Assertions...
        $this->artisan('skeleton')
            ->expectsOutput('All done')
            ->assertExitCode(0);
    }
}
