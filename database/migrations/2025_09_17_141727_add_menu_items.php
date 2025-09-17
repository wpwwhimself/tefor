<?php

use App\Models\Shipyard\NavItem;
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
        $items = [
            [
                "name" => "Dzisiaj",
                "visible" => 1,
                "order" => 1,
                "icon" => "home",
                "target_type" => 1,
                "target_name" => "calendar.today",
            ],
            [
                "name" => "Kalendarz",
                "visible" => 1,
                "order" => 2,
                "icon" => "calendar",
                "target_type" => 1,
                "target_name" => "calendar.show",
            ],
            [
                "name" => "Sesje",
                "visible" => 1,
                "order" => 3,
                "icon" => "clock",
                "target_type" => 1,
                "target_name" => "calendar.sessions",
            ],
            [
                "name" => "Uczniowie",
                "visible" => 1,
                "order" => 4,
                "icon" => "account-school",
                "target_type" => 1,
                "target_name" => "students.list",
            ],
            [
                "name" => "Podliczenia",
                "visible" => 1,
                "order" => 5,
                "icon" => "chart-bar",
                "target_type" => 1,
                "target_name" => "stats.index",
            ],
        ];

        foreach ($items as $item) {
            $created_item = NavItem::create($item);
            $created_item->roles()->sync(["teacher"]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        NavItem::whereIn("name", [
            "Dzisiaj",
            "Kalendarz",
            "Sesje",
            "Uczniowie",
            "Podliczenia",
        ])->delete();
    }
};
