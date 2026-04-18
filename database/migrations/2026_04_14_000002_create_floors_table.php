<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('floors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factory_id')->constrained('factories')->cascadeOnDelete();
            $table->string('name');
            $table->integer('floor_number');
            $table->timestamps();
            $table->unique(['factory_id', 'floor_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('floors');
    }
};
