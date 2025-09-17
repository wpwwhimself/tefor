<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table("student_statuses")->insert([
            [
                "name" => "Ważniejszy",
                "color" => "#c6ad22ff",
                "icon" => "star",
                "index" => 1,
            ],
            [
                "name" => "Normalny",
                "color" => "#23921f",
                "icon" => "circle",
                "index" => 2,
            ],
            [
                "name" => "Rzadki",
                "color" => "#2464c3ff",
                "icon" => "timer-sand",
                "index" => 3,
            ],
            [
                "name" => "Archiwalny",
                "color" => "#637871ff",
                "icon" => "archive",
                "index" => 4,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table("student_statuses")->truncate();
    }
};
