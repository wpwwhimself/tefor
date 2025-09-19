<?php

use App\Models\Shipyard\Modal;
use App\Models\Student;
use App\Models\StudentStatus;
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
            "name" => "create-student",
            "visible" => 1,
            "heading" => "Utwórz ucznia",
            "fields" => [
                [
                    "name",
                    $fields["name"]["type"],
                    $fields["name"]["label"],
                    $fields["name"]["icon"],
                    $fields["name"]["required"] ?? false,
                    null,
                ],
                [
                    "nickname",
                    $fields["nickname"]["type"],
                    $fields["nickname"]["label"],
                    $fields["nickname"]["icon"],
                    $fields["nickname"]["required"] ?? false,
                    null,
                ],
                [
                    "student_status_id",
                    "select",
                    "Status",
                    model_icon("student-statuses"),
                    true,
                    json_encode([
                        "selectData" => [
                            "options" => StudentStatus::orderBy("index")->get()
                                ->map(fn ($s) => [
                                    "value" => $s->id,
                                    "label" => $s->name,
                                ]),
                        ]
                    ]),
                ],
                [
                    "default_rate",
                    $fields["default_rate"]["type"],
                    $fields["default_rate"]["label"],
                    $fields["default_rate"]["icon"],
                    $fields["default_rate"]["required"] ?? false,
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
                    $fields["default_rate_below_hour"]["required"] ?? false,
                    json_encode([
                        "min" => $fields["default_rate_below_hour"]["min"],
                        "step" => $fields["default_rate_below_hour"]["step"],
                    ]),
                ],
            ],
            "target_route" => "students.create",
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Modal::where("name", "create-student")->delete();
    }
};
