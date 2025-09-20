<?php

namespace App\Http\Controllers;

use App\Models\StudentSession;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index()
    {
        $lastYearIncomeByMonth = StudentSession::where("started_at", ">=", now()->subYear())
            ->orderBy("started_at")
            ->get()
            ->groupBy(fn ($ss) => $ss->started_at->format("Y-m"))
            ->map(fn ($sss, $month) => [
                "label" => $month,
                "value" => $sss->sum("cost"),
                "value_label" => $sss->sum("cost")." zł, ".$sss->sum("duration_h")." h",
            ]);

        $sections = [
            [
                "title" => "Przychody w ostatnim roku",
                "icon" => "calendar-month",
                "id" => "last-year-income",
            ],
        ];

        return view("pages.stats.index", compact(
            "lastYearIncomeByMonth",
            "sections",
        ));
    }
}
