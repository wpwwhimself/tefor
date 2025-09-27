<?php

use App\Models\Shipyard\Modal;
use App\Models\Shipyard\Setting;
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
        Setting::insert([
            [
                "name" => "stats_range_from",
                "type" => "date",
                "value" => "2000-01-01",
            ],
            [
                "name" => "stats_range_to",
                "type" => "date",
                "value" => "2099-12-31",
            ],
        ]);

        Modal::create([
            "name" => "update-stats-range",
            "heading" => "Zmień zakres podliczeń",
            "visible" => 1,
            "target_route" => "stats.range.update",
            "fields" => [
                [
                    "stats_range_from",
                    "date",
                    "Od",
                    "calendar",
                    true,
                ],
                [
                    "stats_range_to",
                    "date",
                    "Do",
                    "calendar",
                    true,
                ],
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::whereIn("name", [
            "stats_range_from",
            "stats_range_to",
        ])->delete();

        Modal::where("name", "update-stats-range")->delete();
    }
};
