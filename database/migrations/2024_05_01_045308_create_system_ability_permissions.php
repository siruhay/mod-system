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
        Schema::create('system_ability_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->index();
            $table->string('slug', 100)->unique();
            $table->foreignId('ability_id')
                ->constrained('system_abilities')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('ability_page_id')
                ->constrained('system_ability_pages')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('module_id')
                ->constrained('system_modules')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('page_id')
                ->constrained('system_pages')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('permission_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_ability_permission');
    }
};
