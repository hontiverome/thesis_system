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
        Schema::create('Users', function (Blueprint $table) {
            $table->integer('UserID', true);
            $table->string('SchoolID', 30)->unique('schoolid');
            $table->string('FullName', 100);
            $table->string('Email', 100)->unique('email');
            $table->date('BirthDate')->nullable();
            $table->string('PasswordHash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Users');
    }
};
