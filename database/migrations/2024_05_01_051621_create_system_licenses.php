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
        Schema::create('system_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->index();
            $table->foreignId('user_id')
                ->constrained('system_users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('module_id')
                ->constrained('system_modules')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('ability_id')
                ->constrained('system_abilities')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();

            $table->unique(['name', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_licenses');
    }
};
