<?php

namespace Module\System\Supports;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class AuditMigration extends Migration
{
    /**
     * Get the migration connection name.
     */
    public function getConnection(): ?string
    {
        return env('AUDIT_DB_CONNECTION', 'platform');
    }

    /**
     * Get the database connection driver.
     */
    protected function driver(): string
    {
        return DB::connection($this->getConnection())->getDriverName();
    }
}