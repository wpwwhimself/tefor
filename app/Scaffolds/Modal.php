<?php

namespace App\Scaffolds;

use App\Scaffolds\Shipyard\Modal as ShipyardModal;

class Modal extends ShipyardModal
{
    protected static function items(): array
    {
        return [
            "create-student" => [
                "heading" => "Utwórz ucznia",
                "target_route" => "students.create",
                "fields" => [
                    [
                        "name" => "name",
                        "type" => "text",
                        "label" => "Nazwa",
                        "icon" => "badge-account",
                        "required" => true
                    ],
                    [
                        "name" => "nickname",
                        "type" => "text",
                        "label" => "Pseudonim",
                        "icon" => "text-short",
                    ],
                    [
                        "name" => "student_status_id",
                        "type" => "select",
                        "label" => "Status",
                        "icon" => "list-status",
                        "required" => true,
                        "extra" => [
                            "selectData" => [
                                "options" => [
                                    ["value" => 1, "label" => "Ważniejszy"],
                                    ["value" => 2, "label" => "Normalny"],
                                    ["value" => 3, "label" => "Rzadki"],
                                    ["value" => 4, "label" => "Archiwalny"]
                                ]
                            ]
                        ]
                    ],
                    [
                        "name" => "cash_for_60_min",
                        "type" => "number",
                        "label" => "Ile uczeń płaci za godzinę sesji?",
                        "icon" => "cash",
                        "required" => true,
                        "extra" => ["min" => 0, "step" => 0.01]
                    ],
                    [
                        "name" => "cash_for_45_min",
                        "type" => "number",
                        "label" => "Ile uczeń płaci za 45 min sesji?",
                        "icon" => "cash",
                        "required" => true,
                        "extra" => ["min" => 0, "step" => 0.01]
                    ]
                ],
            ],
            "update-default-rates" => [
                "heading" => "Aktualizuj stawki",
                "target_route" => "students.rates.update",
                "fields" => [
                    [
                        "name" => "cash_for_60_min",
                        "type" => "number",
                        "label" => "Ile uczeń płaci za godzinę sesji?",
                        "icon" => "cash",
                        "required" => true,
                        "extra" => ["min" => 0, "step" => 0.01]
                    ],
                    [
                        "name" => "cash_for_45_min",
                        "type" => "number",
                        "label" => "Ile uczeń płaci za 45 min sesji?",
                        "icon" => "cash",
                        "required" => true,
                        "extra" => ["min" => 0, "step" => 0.01]
                    ]
                ],
            ],
            "update-stats-range" => [
                "heading" => "Zmień zakres podliczeń",
                "target_route" => "stats.range.update",
                "fields" => [
                    [
                        "name" => "stats_range_from",
                        "type" => "date",
                        "label" => "Od",
                        "icon" => "calendar",
                        "required" => true
                    ],
                    [
                        "name" => "stats_range_to",
                        "type" => "date",
                        "label" => "Do",
                        "icon" => "calendar",
                        "required" => true
                    ]
                ],
            ],
            "stats-for-student" => [
                "heading" => "Wybierz ucznia do podliczeń",
                "target_route" => "stats.pick-student",
                "fields" => [
                    [
                        "name" => "student_id",
                        "type" => "select",
                        "label" => "Uczeń",
                        "icon" => "account-school",
                        "extra" => [
                            "selectData" => [
                                "optionsFromScope" => [
                                    "App\\Models\\Student",
                                    "forPicking",
                                    "option_label",
                                    "id"
                                ],
                                "emptyOption" => "brak (podlicz wszystko)"
                            ]
                        ]
                    ]
                ],
            ]
        ];
    }
}
