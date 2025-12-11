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
        Schema::table('UserRoles', function (Blueprint $table) {
            $table->foreign(['RoleID'], 'fk_userroles_role')->references(['RoleID'])->on('Roles')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['UserID'], 'fk_userroles_user')->references(['UserID'])->on('Users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('UserRoles', function (Blueprint $table) {
            $table->dropForeign('fk_userroles_role');
            $table->dropForeign('fk_userroles_user');
        });
    }
};
