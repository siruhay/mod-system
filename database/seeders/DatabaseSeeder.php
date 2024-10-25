<?php

namespace ModuleSystem\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->command->call('module:migrate', ['module' => 'System']);
        
        $this->call(SystemDataSeeder::class);
        $this->call(SystemBaseSeeder::class);
        $this->call(SystemUserSeeder::class);
    }
}
