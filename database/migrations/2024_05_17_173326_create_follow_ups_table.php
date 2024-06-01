<?php

use App\Enums\FollowUpStatus;
use App\Models\Lead;
use App\Models\User;
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
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->enum('status', FollowUpStatus::values());
            $table->unsignedInteger('order_column');

            $table->string('name', 100);
            $table->string('email', 255);
            $table->string('phone', 20);
            $table->string('subject', 100)->nullable();
            $table->string('message', 500)->nullable();

            $table->foreignIdFor(User::class)
                ->constrained(table: 'users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
