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
        Schema::create('webinars', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('subject');
            $table->string('description');
            $table->string('startTime');
            $table->string('endTime');
            $table->string('type');

            $table->string('webinarKey');
            $table->string('recurrenceKey');

            $table->boolean('canceled')->default(0);

            $table->foreignId('user_id')->constrained('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webinars', function (Blueprint $table) {
            
            $table->dropForeign(['user_id']);

        });

        Schema::dropIfExists('webinars');
    }
};
