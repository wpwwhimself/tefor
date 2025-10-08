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
        foreach ([
            ["create-student", [
                "default_rate" => [
                    "cash_for_60_min",
                    "number",
                    "Ile uczeń płaci za godzinę sesji?",
                    "cash",
                    true,
                    [
                        "min" => 0,
                        "step" => 0.01,
                    ],
                ],
                "default_rate_below_hour" => [
                    "cash_for_45_min",
                    "number",
                    "Ile uczeń płaci za 45 min sesji?",
                    "cash",
                    true,
                    [
                        "min" => 0,
                        "step" => 0.01,
                    ],
                ],
            ]],
            ["update-default-rates", [
                "default_rate" => [
                    "cash_for_60_min",
                    "number",
                    "Ile uczeń płaci za godzinę sesji?",
                    "cash",
                    true,
                    [
                        "min" => 0,
                        "step" => 0.01,
                    ],
                ],
                "default_rate_below_hour" => [
                    "cash_for_45_min",
                    "number",
                    "Ile uczeń płaci za 45 min sesji?",
                    "cash",
                    true,
                    [
                        "min" => 0,
                        "step" => 0.01,
                    ],
                ],
            ]],
        ] as [$modal_name, $fields_to_change]) {
            $modal = Modal::where("name", $modal_name)->first();
            $fields = $modal->fields;

            foreach ($fields_to_change as $original_field_name => $field_data) {
                $fields = $fields->map(fn ($d) => $d[0] != $original_field_name
                    ? $d
                    : $field_data
                );
            }

            $modal->fields = $fields;
            $modal->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ([
            ["create-student", [
                "cash_for_60_min" => [
                    "default_rate",
                    "number",
                    "Domyślna stawka",
                    "cash",
                    false,
                    [
                        "min" => 0,
                        "step" => 0.01,
                    ],
                ],
                "cash_for_45_min" => [
                    "default_rate_below_hour",
                    "number",
                    "Domyślna stawka (poniżej godziny)",
                    "cash",
                    false,
                    [
                        "min" => 0,
                        "step" => 0.01,
                    ],
                ],
            ]],
            ["update-default-rates", [
                "cash_for_60_min" => [
                    "default_rate",
                    "number",
                    "Domyślna stawka",
                    "cash",
                    false,
                    [
                        "min" => 0,
                        "step" => 0.01,
                    ],
                ],
                "cash_for_45_min" => [
                    "default_rate_below_hour",
                    "number",
                    "Domyślna stawka (poniżej godziny)",
                    "cash",
                    false,
                    [
                        "min" => 0,
                        "step" => 0.01,
                    ],
                ],
            ]],
        ] as [$modal_name, $fields_to_change]) {
            $modal = Modal::where("name", $modal_name)->first();
            $fields = $modal->fields;

            foreach ($fields_to_change as $original_field_name => $field_data) {
                $fields = $fields->map(fn ($d) => $d[0] != $original_field_name
                    ? $d
                    : $field_data
                );
            }

            $modal->fields = $fields;
            $modal->save();
        }
    }
};
