<?php

namespace Module\System\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BaseImport implements WithMultipleSheets
{
    /**
     * The construct function
     *
     * @param [type] $command
     * @param string $mode
     */
    public function __construct(protected $command) {}

    /**
     * The sheets function
     *
     * @return array
     */
    public function sheets(): array
    {
        $this->command->info('install:module-system');

        return [
            'module' => new ModuleImport($this->command),
            'pages' => new PageImport($this->command),
            'permissions' => new PermissionImport($this->command),
            'abilities' => new AbilityImport($this->command),
            'abilities-pages' => new AbilityPageImport($this->command),
            'abilities-permissions' => new AbilityPermissionImport($this->command),
        ];
    }
}
