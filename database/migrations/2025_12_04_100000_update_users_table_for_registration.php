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
        Schema::table('users', function (Blueprint $table) {
            // Drop the single 'name' column
            $table->dropColumn('name');

            // Add first_name, last_name, and id_number
            $table->string('first_name')->nullable()->after('id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('id_number')->unique()->after('last_name');
            $table->date('birth_date')->nullable()->after('id_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');

            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('id_number');
            $table->dropColumn('birth_date');
        });
    }
};
