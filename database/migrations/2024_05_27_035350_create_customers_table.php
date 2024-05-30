<?php

use App\Enums\MaritalStatus;
use App\Models\Customer;
use App\Models\Person;
use App\Models\RealEstate;
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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('filiation_mother', 100)->nullable();
            $table->string('filiation_father', 100)->nullable();
            $table->string('profession', 60)->nullable();
            $table->enum('marital_status', MaritalStatus::values());

            $table->foreignIdFor(Person::class)
                ->unique()
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();
        });

        Schema::create('estate_owner', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(RealEstate::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignIdFor(Customer::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
        Schema::dropIfExists('estate_owner');
    }
};
