<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animation_usages', function (Blueprint $table) {
            $table->id();
            $table->string('user_token')->index();
            $table->string('animation_type')->index();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->timestamp('used_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animation_usages');
    }
};