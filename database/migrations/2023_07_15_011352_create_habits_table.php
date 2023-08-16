<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('habits', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('name');
            $table->string('description');
            $table->string('frequency');
            $table->string('scheduled_to')->nullable();
            $table->json('occurrence_days')->nullable();
            $table->integer('streak')->default(0);
            $table->string('icon')->default('Default');
            $table->string('colour')->default('#00cedf');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
