<?php

use Module\System\Models\SystemModule;

if (! function_exists('module')) {
    function module($moduleId = null): SystemModule | null
    {
        if (!is_string($moduleId)) {
            return SystemModule::find($moduleId);
        }

        if (is_string($moduleId)) {
            return SystemModule::firstWhere('slug', $moduleId);
        }

        return (new SystemModule());
    }
}