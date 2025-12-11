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
        Schema::create('GroupAdvisers', function (Blueprint $table) {
            $table->string('GroupID', 10);
            $table->integer('AdviserUserID')->index('fk_advisers_user');

            $table->primary(['GroupID', 'AdviserUserID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('GroupAdvisers');
    }
};
