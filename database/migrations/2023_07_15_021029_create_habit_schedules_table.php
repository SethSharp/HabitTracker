<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('habit_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('habit_id');
            $table->integer('user_id');
            $table->date('scheduled_completion');
            $table->boolean('completed')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
