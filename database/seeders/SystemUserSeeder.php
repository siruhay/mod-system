<?php

namespace ModuleSystem\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Module\System\Models\SystemUser;

class SystemUserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $superadmin = SystemUser::create([
            'name' =>  'superadmin',
            'email' => env('ADMIN_EMAIL', 'monoland@dev'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'P@ssw0rd')),
            'debuger' => true,
            'secured' => true,
        ]);

        $superadmin->addLicense('system-superadmin');
        $superadmin->addLicense('account-superadmin');
    }
}
