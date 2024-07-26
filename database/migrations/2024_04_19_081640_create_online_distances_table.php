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
        Schema::create('online_distances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_date_loc_id')->constrained('user_date_locs')->cascadeOnDelete();
            $table->foreignId('lab_loc_id')->constrained('lab_locs')->cascadeOnDelete();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_distances');
    }
};
