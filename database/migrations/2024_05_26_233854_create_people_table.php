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
        Schema::create('people', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100);
            $table->enum('type', ['F', 'J']);
            $table->string('num_registry', 14)->unique();
            $table->string('num_identity', 20)->nullable();
            $table->date('birthdate');

            $table->char('zip_code', 8);
            $table->char('address_state', 2);
            $table->string('address_city', 150);
            $table->string('address_neighborhood', 100);
            $table->string('address_street', 150);
            $table->string('address_number', 50);
            $table->string('address_complement')->nullable();

            $table->string('phone_1', 20);
            $table->string('phone_2', 20)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
