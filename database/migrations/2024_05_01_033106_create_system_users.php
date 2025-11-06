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
        Schema::create('system_users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->index();
            $table->string('email', 50)->unique();
            $table->enum('gender', ['male', 'female', 'oauth'])->default('male');
            $table->string('emailgov')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 100)->index();
            $table->timestamp('password_updated_at')->nullable();
            $table->string('two_factor_secret', 255)->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->string('avatar', 200)->nullable();
            $table->string('theme', 50)->default('blue-grey');
            $table->string('highlight', 50)->default('yellow');
            $table->nullableMorphs('userable');
            $table->boolean('debuger')->default(false);
            $table->boolean('secured')->default(false);
            $table->jsonb('last_geolocation')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('system_users');
    }
};
