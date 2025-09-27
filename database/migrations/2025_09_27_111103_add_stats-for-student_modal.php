<?php

use App\Models\Shipyard\Modal;
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
        Modal::create([
            "name" => "stats-for-student",
            "visible" => 1,
            "heading" => "Wybierz ucznia do podliczeń",
            "target_route" => "stats.pick-student",
            "fields" => [
                [
                    "student_id",
                    "select",
                    "Uczeń",
                    model_icon("students"),
                    true,
                    [
                        "selectData" => [
                            "optionsFromScope" => [
                                "App\Models\Student",
                                "forPicking",
                                "option_label",
                                "id",
                            ],
                            "emptyOption" => "brak (podlicz wszystko)",
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Modal::where("name", "stats-for-student")->delete();
    }
};
