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
        Schema::create('system_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->string('icon', 25);
            $table->string('color', 25)->default('blue-grey');
            $table->string('highlight', 25)->default('white');
            $table->enum('type', ['account', 'administrator', 'personal'])->default('administrator');
            $table->string('domain', 50)->index()->nullable();
            $table->string('prefix', 50)->index()->nullable();
            $table->nullableMorphs('ownerable');
            $table->string('git_address')->nullable();
            $table->string('git_version', 15)->nullable();
            $table->jsonb('meta')->nullable();
            $table->boolean('check_for_update')->default(false);
            $table->boolean('desktop')->default(true);
            $table->boolean('mobile')->default(true);
            $table->boolean('enabled')->default(true);
            $table->boolean('published')->default(true);
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
        Schema::dropIfExists('system_modules');
    }
};
