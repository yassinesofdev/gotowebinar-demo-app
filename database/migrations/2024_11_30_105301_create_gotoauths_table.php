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
        Schema::create('gotoauths', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('organizer_key')->nullable();

            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();

            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->string('token_type')->nullable();
            $table->integer('expires_in')->nullable();
            $table->text('scope')->nullable();
            $table->string('principal')->nullable(); // Store the admin email or username associated with the token

            $table->foreignId('user_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('gotoauths', function (Blueprint $table) {
            
            $table->dropForeign(['user_id']);

        });

        Schema::dropIfExists('gotoauths');
    }
};
