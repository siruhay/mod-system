<?php

namespace Module\System\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Module\System\Models\SystemModule;

class ModuleImport implements ToCollection, WithHeadingRow
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
        $this->command->info('seed:table-module');
        $this->command->getOutput()->progressStart(count($rows));

        foreach ($rows as $row) {
            $this->command->getOutput()->progressAdvance();

            // check existing module
            if (!$model = SystemModule::firstWhere('slug', $row['slug'])) {
                $model = new SystemModule();
            } else {
                // when the module exists
                // remove all page, ability and license
                $model->pages()->forceDelete();
                $model->abilities()->forceDelete();
                $model->licenses()->forceDelete();
            }

            $model->name = $row['name'];
            $model->slug = $row['slug'];
            $model->icon = $row['icon'];
            $model->color = $row['color'];
            $model->type = $row['type'];
            $model->domain = $row['domain'];
            $model->prefix = $row['prefix'];
            $model->enabled = array_key_exists('enabled', $row->toArray()) ? (bool) $row['enabled'] : true;
            $model->desktop = array_key_exists('desktop', $row->toArray()) ? (bool) $row['desktop'] : true;
            $model->mobile = array_key_exists('mobile', $row->toArray()) ? (bool) $row['mobile'] : true;
            $model->describe = $row['describe'];
            $model->save();
        }

        $this->command->getOutput()->progressFinish();
    }
}
