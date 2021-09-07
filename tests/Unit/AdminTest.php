<?php

namespace NagSamayam\AdminFortify\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NagSamayam\AdminFortify\Models\Admin;
use NagSamayam\AdminFortify\Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_has_email()
    {
        $admin = Admin::factory()->create([
            'full_name' => 'Nageswara Rao',
            'email' => 'nag.samayam@gmail.com',
        ]);

        $this->assertEquals('nag.samayam@gmail.com', $admin->getEmail());
    }
}
