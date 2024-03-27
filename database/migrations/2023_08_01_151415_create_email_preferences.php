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
            $table->boolean('daily_reminder')->default(false);
            $table->boolean('goal_reminder')->default(false);
            $table->timestamps();
        });
    }
};
