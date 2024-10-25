<?php

namespace Module\System\Imports;

use Illuminate\Support\Collection;
use Module\System\Models\SystemRole;
use Module\System\Models\SystemModule;
use Module\System\Models\SystemAbility;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AbilityImport implements ToCollection, WithHeadingRow
{
    /**
     * The construct function
     *
     * @param [type] $command
     * @param string $mode
     */
    public function __construct(protected $command) {}

    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        $this->command->info('seed:table-ability');
        $this->command->getOutput()->progressStart(count($rows));

        foreach ($rows as $row) {
            $this->command->getOutput()->progressAdvance();

            $module = SystemModule::firstWhere('slug', $row['module']);
            $role = SystemRole::firstWhere('slug', $row['role']);

            if (!$module || !$role) {
                return;
            }

            /** MAP SLUG DATA */
            // {MODULE}-{ROLE}
            // eq: {system}-{superadmin}
            $abilityName = str($row['module'] . ' ' . $row['role'])->slug()->toString();

            /** CREATE NEW RECORD */
            if (!$model = SystemAbility::firstWhere('name', $abilityName)) {
                $model = new SystemAbility();
            }

            $model->name = $abilityName;
            $model->module_id = $module->id;
            $model->role_id = $role->id;
            $model->save();
        }

        $this->command->getOutput()->progressFinish();
    }
}
