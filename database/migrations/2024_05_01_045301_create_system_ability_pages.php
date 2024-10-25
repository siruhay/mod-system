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
        Schema::create('system_ability_pages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->index();
            $table->string('slug', 50)->unique();
            $table->foreignId('ability_id')
                ->constrained('system_abilities')
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
        Schema::dropIfExists('system_ability_page');
    }
};
