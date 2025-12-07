<?php

use Illuminate.Database\Migrations\Migration;
use Illuminate.Database\Schema\Blueprint;
use Illuminate.Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_advisers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups', 'group_id')->cascadeOnDelete();
            $table->foreignId('adviser_user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }
};