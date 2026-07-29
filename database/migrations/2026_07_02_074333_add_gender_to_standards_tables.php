<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['weight_standards', 'height_standards', 'head_circumference_standards'];
        
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->dropUnique($tableName . '_age_months_unique');
                $table->enum('gender', ['male', 'female'])->default('male')->after('age_months');
                $table->unique(['age_months', 'gender']);
            });
        }
    }

    public function down(): void
    {
        $tables = ['weight_standards', 'height_standards', 'head_circumference_standards'];
        
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->dropUnique($tableName . '_age_months_gender_unique');
                $table->dropColumn('gender');
                $table->unique('age_months');
            });
        }
    }
};
