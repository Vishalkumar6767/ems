<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'company_admin', 'employee'])->default('employee')->after('email');
            $table->foreignId('factory_id')->nullable()->after('role')->constrained('factories')->nullOnDelete();
            $table->index('role');
            $table->index('factory_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['factory_id']);
            $table->dropColumn(['role', 'factory_id']);
        });
    }
};
