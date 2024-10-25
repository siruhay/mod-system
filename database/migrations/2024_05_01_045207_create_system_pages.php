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
        Schema::create('system_pages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->index();
            $table->string('slug', 50)->unique();
            $table->string('title', 50)->nullable();
            $table->string('icon', 25)->default('home');
            $table->string('path', 100)->index();
            $table->boolean('side')->default(false);
            $table->boolean('dock')->default(false);
            $table->boolean('enabled')->default(true);
            $table->foreignId('module_id')
                ->constrained('system_modules')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->jsonb('meta')->nullable();
            $table->nestedSet();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_pages');
    }
};
