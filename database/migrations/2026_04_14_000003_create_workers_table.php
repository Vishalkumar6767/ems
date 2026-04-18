<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floor_id')->constrained('floors')->cascadeOnDelete();
            $table->string('name');
            $table->string('employee_id')->unique();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
