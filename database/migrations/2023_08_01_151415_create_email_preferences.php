<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_preferences', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->boolean('daily_reminder');
            $table->boolean('goal_reminder');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_preferences');
    }
};
