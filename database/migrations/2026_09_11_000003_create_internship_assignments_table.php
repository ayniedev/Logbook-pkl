<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->constrained('users');
            $table->foreignId('division_id')->constrained('divisions');
            $table->foreignId('mentor_id')->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_assignments');
    }
};
