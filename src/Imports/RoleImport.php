<?php

namespace Module\System\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Module\System\Models\SystemRole;

class RoleImport implements ToCollection, WithHeadingRow
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
        $this->command->info('seed:table-system_roles');
        $this->command->getOutput()->progressStart(count($rows));

        foreach ($rows as $row) {
            $this->command->getOutput()->progressAdvance();

            /** CREATE NEW RECORD */
            $model = new SystemRole();
            $model->name = $row['name'];
            $model->slug = $row['slug'];
            $model->save();
        }

        $this->command->getOutput()->progressFinish();
    }
}
