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
        Schema::create('real_estates', function (Blueprint $table) {
            $table->id();

            $table->char('zip_code', 8);
            $table->char('address_state', 2);
            $table->string('address_city', 150);
            $table->string('address_neighborhood', 100);
            $table->string('address_street', 150);
            $table->string('address_number', 50);
            $table->string('address_complement')->nullable();

            $table->string('type');
            $table->string('title');
            $table->string('description');
            $table->decimal('area_total');
            $table->decimal('area_built');
            $table->tinyInteger(column: 'num_rooms', unsigned: true)->nullable();
            $table->tinyInteger(column: 'num_suite', unsigned: true)->nullable();
            $table->tinyInteger(column: 'num_garage', unsigned: true)->nullable();

            $table->decimal('price', 11, 2);
            $table->decimal('tax_iptu');
            $table->decimal('tax_condominium')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_estates');
    }
};
