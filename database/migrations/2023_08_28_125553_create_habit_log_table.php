<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('habit_log', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('habit_id');
            $table->string('log_description');
            $table->string('log_type');
            $table->timestamps();
        });
    }
};
