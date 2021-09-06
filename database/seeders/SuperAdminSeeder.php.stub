<?php

namespace NagSamayam\AdminFortify\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use NagSamayam\AdminFortify\Actions\CreateNewAdmin;
use NagSamayam\AdminFortify\Dtos\CreateNewAdminData;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = DB::table('admins')->count();
        if ($count === 0) {
            $this->command->info("Admins started seeding!");

            $adminData = new CreateNewAdminData([
                'fullName' => 'Steve Waugh',
                'email' => 'stevewaughbitrix@gmail.com',
            ]);

            app(CreateNewAdmin::class)($adminData);

            $count = DB::table('admins')->count();
            $this->command->info("Seeded {$count} admins...");
        } else {
            $this->command->alert("{$count} admins already seeded!");
        }
    }
}
