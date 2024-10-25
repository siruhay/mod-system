<?php

namespace ModuleSystem\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Module\System\Imports\DataImport;

class SystemDataSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $path = base_path(
            'modules' . DIRECTORY_SEPARATOR .
                'system' . DIRECTORY_SEPARATOR .
                'database' . DIRECTORY_SEPARATOR .
                'masters' . DIRECTORY_SEPARATOR .
                'data-seeder.xlsx'
        );

        if (File::exists($path)) {
            Excel::import(new DataImport($this->command), $path);
        }
    }
}
