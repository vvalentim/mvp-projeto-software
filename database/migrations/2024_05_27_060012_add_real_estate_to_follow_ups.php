<?php

use App\Models\Customer;
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
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->foreignIdFor(RealEstate::class)
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignIdFor(Customer::class)
                ->nullable()
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
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->dropColumn('real_estate_id');
            $table->dropColumn('customer_id');
        });
    }
};
