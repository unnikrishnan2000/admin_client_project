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
        if (!Schema::hasTable('ui_blocks')) {
            Schema::create('ui_blocks', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->enum('type', ['banner', 'card', 'list', 'stats'])->default('banner');
                $table->boolean('status')->default(true);
                $table->integer('order')->default(1);
                $table->json('config')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ui_blocks');
    }
};
