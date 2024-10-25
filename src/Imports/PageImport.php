<?php

namespace Module\System\Imports;

use Illuminate\Support\Collection;
use Module\System\Models\SystemPage;
use Module\System\Models\SystemModule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PageImport implements ToCollection, WithHeadingRow
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
        $this->command->info('seed:table-page');
        $this->command->getOutput()->progressStart(count($rows));

        foreach ($rows as $row) {
            $this->command->getOutput()->progressAdvance();

            $module = SystemModule::firstWhere('slug', $row['module']);
            
            if (!$module) {
                continue;
            }

            $parent = $row['parent'] ? SystemPage::firstWhere('slug', $row['parent']) : null;

            /** CREATE NEW RECORD */
            $model = new SystemPage();
            $model->name = $row['name'];
            $model->slug = $row['slug'];
            $model->title = $row['title'];
            $model->icon = $row['icon'];
            $model->path = $row['path'];
            $model->side = (bool) $row['side'];
            $model->dock = (bool) $row['dock'];
            $model->enabled = array_key_exists('enabled', $row->toArray()) ? (bool) $row['enabled'] : true;
            $model->module_id = $module->id;
            $model->parent_id = optional($parent)->id;
            $model->save();
        }

        $this->command->getOutput()->progressFinish();
    }
}
