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
        Schema::create('animation_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('animation_name');
            $table->string('session_token')->index();
            $table->string('ip_address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animation_statistics');
    }
};
