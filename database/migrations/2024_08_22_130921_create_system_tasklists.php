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
        Schema::create('system_tasklists', function (Blueprint $table) {
            $table->id();
            $table->text('name', 200)->index();
            $table->text('type', 50)->index();
            $table->nullableMorphs('taskable');
            $table->enum('state', ['pending', 'approved', 'rejected'])
                ->index()
                ->default('pending');
            $table->text('filepath')->nullable();
            $table->foreignId('audited_id')->nullable();
            $table->foreignId('officer_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_tasklists');
    }
};
