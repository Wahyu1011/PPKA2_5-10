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
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('nim')->nullable();
            $table->string('department')->nullable();
            $table->string('organization')->nullable();
            $table->string('phone')->nullable();
            $table->string('activity_name')->nullable();
            $table->integer('participant_count')->nullable();
            $table->boolean('commitment_agreed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'nim', 'department', 'organization', 'phone', 
                'activity_name', 'participant_count', 'commitment_agreed'
            ]);
        });
    }
};
