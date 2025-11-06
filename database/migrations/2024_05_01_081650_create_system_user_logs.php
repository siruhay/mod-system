<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Module\System\Supports\AuditMigration;

return new class () extends AuditMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_user_logs', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('event', 15)->index()->default('created');
            $table->string('module')->index();
            $table->morphs('auditable');
            $table->jsonb('dirties')->nullable();
            $table->jsonb('origins')->nullable();
            $table->foreignId('user_id');
            $table->string('user_name')->index()->default('system');
            $table->boolean('impersonate')->index()->default(false);
            $table->foreignId('impersonate_id')->nullable();
            $table->string('impersonate_name')->index()->nullable();
            $table->text('message')->nullable();
            $table->jsonb('coords')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_user_logs');
    }
};
