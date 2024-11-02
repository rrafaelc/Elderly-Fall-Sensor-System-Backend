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
        Schema::connection('mysql')->create('persons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('date_of_birth')->nullable()->default('2024-11-02');
            $table->string('blood_type')->nullable()->default("1");
            $table->string('rg')->nullable()->default("1");
            $table->string('cpf');
            $table->string('street')->nullable()->default("1");
            $table->string('street_number')->nullable()->default("1");
            $table->string('neighborhood')->nullable()->default("1");
            $table->string('city')->nullable()->default("1");
            $table->string('state', 2)->nullable()->default("1");
            $table->string('zip_code', 9)->nullable()->default("1");
            $table->string('conditions')->nullable()->default("1");
            $table->string('name', 50);
            $table->string('email', 50);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('persons');
    }
};
