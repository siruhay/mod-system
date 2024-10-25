<?php

namespace Module\System\Imports;

use Illuminate\Support\Collection;
use Module\System\Models\SystemPage;
use Module\System\Models\SystemPermission;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PermissionImport implements ToCollection, WithHeadingRow
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
        $this->command->info('seed:table-permission');
        $this->command->getOutput()->progressStart(count($rows));

        foreach ($rows as $row) {
            $this->command->getOutput()->progressAdvance();

            $page = SystemPage::firstWhere('slug', $row['page']);

            if (!$page) {
                continue;
            }

            /** CREATE NEW RECORD */
            $permissions = array_map('trim', str($row['permissions'])->explode(',')->toArray());
            
            foreach ($permissions as $permission) {
                if (!in_array($permission, ['view', 'create', 'show', 'update', 'delete', 'restore', 'destroy'])) {
                    continue;
                }
                
                // MAP SLUG OF PERMISSION
                // {PERMISSION}-{PAGE}
                // eq: {view}-{system-dashboard}
                $permissionSlug = str($permission . ' ' . $row['page'])->slug()->toString();

                if (!$model = SystemPermission::firstWhere('slug', $permissionSlug)) {
                    $model = new SystemPermission();
                }

                $model->name = $permission;
                $model->slug = $permissionSlug;
                $model->page_id = $page->id;
                $model->module_id = $page->module_id;
                $model->save();
            }
        }

        $this->command->getOutput()->progressFinish();
    }
}
