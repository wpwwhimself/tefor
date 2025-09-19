<?php

use App\Models\Shipyard\Modal;
use App\Models\Student;
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
        $fields = Student::fields();

        Modal::create([
            "name" => "update-default-rates",
            "visible" => 1,
            "heading" => "Aktualizuj stawki",
            "fields" => [
                [
                    "default_rate",
                    $fields["default_rate"]["type"],
                    $fields["default_rate"]["label"],
                    $fields["default_rate"]["icon"],
                    true,
                    json_encode([
                        "min" => $fields["default_rate"]["min"],
                        "step" => $fields["default_rate"]["step"],
                    ]),
                ],
                [
                    "default_rate_below_hour",
                    $fields["default_rate_below_hour"]["type"],
                    $fields["default_rate_below_hour"]["label"],
                    $fields["default_rate_below_hour"]["icon"],
                    true,
                    json_encode([
                        "min" => $fields["default_rate"]["min"],
                        "step" => $fields["default_rate"]["step"],
                    ]),
                ],
            ],
            "target_route" => "students.rates.update",
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Modal::where("name", "update-default-rates")->delete();
    }
};
