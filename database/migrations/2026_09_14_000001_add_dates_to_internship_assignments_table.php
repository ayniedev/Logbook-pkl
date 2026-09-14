<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internship_assignments', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('mentor_id');
            $table->unsignedInteger('duration_days')->nullable()->after('start_date');
        });
    }

    public function down(): void
    {
        Schema::table('internship_assignments', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'duration_days']);
        });
    }
};
