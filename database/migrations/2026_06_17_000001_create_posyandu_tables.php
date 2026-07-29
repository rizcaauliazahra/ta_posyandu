<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->timestamps();
        });

        Schema::create('weight_standards', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('age_months')->unique();
            $table->string('age_label');
            $table->decimal('min_weight', 5, 2);
            $table->decimal('max_weight', 5, 2);
            $table->timestamps();
        });

        Schema::create('height_standards', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('age_months')->unique();
            $table->string('age_label');
            $table->decimal('min_height', 5, 2);
            $table->decimal('max_height', 5, 2);
            $table->timestamps();
        });

        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('status')->unique();
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->cascadeOnDelete();
            $table->decimal('weight', 5, 2);
            $table->decimal('height', 5, 2);
            $table->date('measurement_date');
            $table->time('measurement_time');
            $table->unsignedSmallInteger('age_months')->default(12);
            $table->string('weight_status');
            $table->string('height_status');
            $table->text('recommendation')->nullable();
            $table->timestamps();
        });

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('measurement');
        Schema::dropIfExists('recommendations');
        Schema::dropIfExists('height_standards');
        Schema::dropIfExists('weight_standards');
        Schema::dropIfExists('children');
    }
};
