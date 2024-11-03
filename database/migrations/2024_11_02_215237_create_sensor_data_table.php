<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::connection('mysql')->create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number');
            $table->string('event_type');
            $table->boolean('is_fall');
            $table->boolean('is_impact');
            $table->float('ax');
            $table->float('ay');
            $table->float('az');
            $table->float('gx');
            $table->float('gy');
            $table->float('gz');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('sensor_data', function (Blueprint $table) {
            //
        });
    }
};
