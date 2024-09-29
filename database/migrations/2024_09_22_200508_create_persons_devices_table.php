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
        Schema::create('persons_devices', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('person_id')->constrained('persons');
            $table->foreignId('device_id')->constrained('devices');
            $table->foreignId('user_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persons_devices');
    }
};
