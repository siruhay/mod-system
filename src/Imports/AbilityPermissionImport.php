<?php

namespace Module\System\Imports;

use Illuminate\Support\Collection;
use Module\System\Models\SystemPage;
use Module\System\Models\SystemModule;
use Module\System\Models\SystemAbility;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Module\System\Models\SystemAbilityPage;
use Module\System\Models\SystemAbilityPermission;
use Module\System\Models\SystemPermission;

class AbilityPermissionImport implements ToCollection, WithHeadingRow
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
        $this->command->info('seed:table-ability-permission');
        $this->command->getOutput()->progressStart(count($rows));

        foreach ($rows as $row) {
            $this->command->getOutput()->progressAdvance();

            $page = SystemPage::firstWhere('slug', $row['page']);

            if (!$page) {
                continue;
            }

            $abilities = $row['role'] === '*' ? 
                with(SystemModule::firstWhere('slug', $row['module']))->abilities->pluck('name') :
                $this->mapRoleToArray($row);

            $permissions = $row['permission'] === '*' ?
                with(SystemPage::firstWhere('slug', $row['page']))->permissions->pluck('slug') : 
                $this->mapPermissionToArray($row);

            foreach ($abilities as $abilityName) {
                $ability = SystemAbility::with(['role'])->firstWhere('name', $abilityName);
                
                if (!$ability) {
                    continue;
                }

                foreach ($permissions as $permissionSlug) {
                    $permission = SystemPermission::firstWhere('slug', $permissionSlug);

                    if (!$permission) {
                        continue;
                    }

                    if ($abilityPage = SystemAbilityPage::where('ability_id', $ability->id)
                        ->where('module_id', $ability->module_id)
                        ->where('page_id', $page->id)
                        ->first()) {
                        $model = new SystemAbilityPermission();
                        $model->name = $permission->slug;
                        $model->slug = str($ability->role->slug . ' ' . $permission->slug)->slug()->toString();
                        $model->ability_id = $ability->id;
                        $model->ability_page_id = $abilityPage->id;
                        $model->module_id = $ability->module_id;
                        $model->page_id = $page->id;
                        $model->permission_id = $permission->id;
                        $model->save();
                    }
                }
            }
        }

        $this->command->getOutput()->progressFinish();
    }

    /**
     * The mapRoleToArray function
     *
     * @param [type] $row
     * @return array
     */
    protected function mapRoleToArray($row): array
    {
        return array_map(function ($role) use ($row) {
            return $row['module'] . '-' . trim($role);
        }, str($row['role'])->explode(',')->toArray());
    }

    /**
     * The mapPermissionToArray function
     *
     * @param [type] $row
     * @return array
     */
    protected function mapPermissionToArray($row): array
    {
        return array_map(function ($permission) use ($row) {
            return trim($permission) . '-' . $row['page'];
        }, str($row['permission'])->explode(',')->toArray());
    }
}
