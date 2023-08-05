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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('avatar')->default('/images/default-avatar.png');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('streak');
            $table->integer('best_streak');
            $table->rememberToken();
            $table->timestamps();
        });
    }
};
