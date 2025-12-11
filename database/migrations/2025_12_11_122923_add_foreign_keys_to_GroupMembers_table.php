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
        Schema::table('GroupMembers', function (Blueprint $table) {
            $table->foreign(['GroupID'], 'fk_members_group')->references(['GroupID'])->on('Groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['StudentUserID'], 'fk_members_student')->references(['UserID'])->on('Users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('GroupMembers', function (Blueprint $table) {
            $table->dropForeign('fk_members_group');
            $table->dropForeign('fk_members_student');
        });
    }
};
