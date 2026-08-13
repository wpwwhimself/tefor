<?php

namespace App\Models;

use Wpwwhimself\Shipyard\Models\Setting as ShipyardSetting;

class Setting extends ShipyardSetting
{
    public const FROM_SHIPYARD = true;

    public static function fields(): array
    {
        /**
         * * hierarchical structure of the page *
         * grouped by sections (title, subtitle, icon, identifier)
         * each section contains fields (name, label, hint, icon)
         */
        return [
            [
                "title" => "Uczniowie",
                "icon" => model_icon("students"),
                "id" => "students",
                "fields" => [
                    [
                        "name" => "students_archival_after_months",
                        "label" => "Archiwizuj ucznia po [mc]",
                        "icon" => "calendar-alert",
                        "hint" => "Codziennie o północy, uczniowie, którzy nie mają nowych sesji w ostatnich n miesiącach, zostają oznaczeni jako archiwalni.",
                    ],
                ],
            ],
            [
                "title" => "Podliczenia",
                "icon" => "chart-bar",
                "id" => "stats",
                "fields" => [
                    [
                        "name" => "stats_range_from",
                        "label" => "Podliczaj od",
                        "icon" => "calendar",
                    ],
                    [
                        "name" => "stats_range_to",
                        "label" => "Podliczaj do",
                        "icon" => "calendar",
                    ],
                ],
            ],
        ];
    }
}
