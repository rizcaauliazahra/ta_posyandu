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
        Schema::create('head_circumference_standards', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('age_months')->unique();
            $table->string('age_label');
            $table->decimal('min_head_circumference', 5, 2);
            $table->decimal('max_head_circumference', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('head_circumference_standards');
    }
};
