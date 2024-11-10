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
        Schema::connection('mysql')->create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->boolean('status')->default(1);
            $table->string('serial_number');
            $table->double('timer')->default(1);
            $table->dateTime('last_verification')->nullable();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('devices');
    }
};
