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
        Schema::create('system_personates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->index();
            $table->foreignId('authorized_id');
            $table->foreignId('authorizer_id');
            $table->date('date_start')->index()->nullable();
            $table->date('date_end')->index()->nullable();
            $table->string('filepath')->nullable();
            $table->string('passphrase')->index()->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_personates');
    }
};
