<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_auditors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->index();
            $table->string('nik', 24)->unique();
            $table->string('address', 255)->nullable();
            $table->string('job', 100)->nullable();
            $table->foreignId('role_id');
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
        Schema::dropIfExists('system_auditors');
    }
};
