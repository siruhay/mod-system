<?php

namespace Module\System\Imports;

use Illuminate\Support\Collection;
use Module\System\Models\SystemPage;
use Module\System\Models\SystemAbility;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Module\System\Models\SystemAbilityPage;
use Module\System\Models\SystemModule;

class AbilityPageImport implements ToCollection, WithHeadingRow
{
    /**
     * The construct function
     *
     * @param [type] $command
     * @param string $mode
     */
    public function __construct(protected $command)
    {
    }

    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        $this->command->info('seed:table-ability-page');
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

            foreach ($abilities as $abilityName) {
                $ability = SystemAbility::with(['role'])->firstWhere('name', $abilityName);

                if (!$ability) {
                    continue;
                }

                $model = new SystemAbilityPage();
                $model->name = $page->slug;
                $model->slug = str($ability->role->slug . ' ' . $page->slug)->slug()->toString();
                $model->ability_id = $ability->id;
                $model->module_id = $ability->module_id;
                $model->page_id = $page->id;
                $model->save();
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
            return str($row['module'] . '-' . trim($role))->slug()->toString();
        }, str($row['role'])->explode(',')->toArray());
    }
}
