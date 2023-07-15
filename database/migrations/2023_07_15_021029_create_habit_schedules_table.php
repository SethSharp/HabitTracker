<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habit_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('habit_id');
            $table->integer('user_id');
            $table->date('scheduled_completion');
            $table->boolean('completed')->default(0);
            $table->timestamps();
        });
    }
};
