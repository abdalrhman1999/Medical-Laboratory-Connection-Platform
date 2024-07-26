<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id(); // تعريف الهوية الرئيسية
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('laboratory_id')->nullable()->constrained('laboratories')->cascadeOnDelete();
            $table->decimal('latitude', 10, 7); // خط العرض
            $table->decimal('longitude', 10, 7); // خط الطول
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('Location');
    }
};