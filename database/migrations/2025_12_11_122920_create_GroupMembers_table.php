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
        Schema::create('GroupMembers', function (Blueprint $table) {
            $table->string('GroupID', 10);
            $table->integer('StudentUserID')->index('fk_members_student');
            $table->string('GroupRole', 20)->nullable();

            $table->primary(['GroupID', 'StudentUserID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('GroupMembers');
    }
};
