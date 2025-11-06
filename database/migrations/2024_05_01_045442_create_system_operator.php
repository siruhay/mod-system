<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_operators', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->index();
            $table->string('biodata_id')->unique();
            $table->foreignId('structural_id')->nullable();
            $table->foreignId('workunit_id')->nullable();
            $table->foreignId('role_id')->nullable();
            $table->foreignId('module_id')->nullable();
            $table->jsonb('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_operator');
    }
};
