<?php

namespace App\Http\Controllers;

use App\Models\Shipyard\Setting;
use App\Models\StudentSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StatsController extends Controller
{
    public function index()
    {
        $data = StudentSession::orderBy("started_at")
            ->where("started_at", ">=", setting("stats_range_from"))
            ->where("started_at", "<=", setting("stats_range_to"))
            ->get();

        $incomeByMonth = $data->groupBy(fn ($ss) => $ss->started_at->format("Y-m"))
            ->map(fn ($sss, $month) => [
                "label" => $month,
                "value" => $sss->sum("cost"),
                "value_label" => $sss->sum("cost")." zł, ".$sss->sum("duration_h")." h",
            ]);
        $incomeByMonth = $this->fillInBlanks($incomeByMonth, "month", [
            "value" => 0,
            "value_label" => "0 zł, 0 h",
        ]);

        $summary = [
            "sums" => [
                "label" => "Sumy",
                "icon" => "counter",
                "data" => [
                    "sessions" => [
                        "label" => "Łącznie sesji",
                        "value" => $data->count(),
                    ],
                    "time" => [
                        "label" => "Łącznie godzin",
                        "value" => $data->sum("duration_h") . " h",
                    ],
                    "income" => [
                        "label" => "Łącznie zarobiono",
                        "value" => $data->sum("cost") . " zł",
                    ],
                ]
            ],
            "avgs" => [
                "label" => "Średnie",
                "icon" => "counter",
                "data" => [
                    "sessions" => [
                        "label" => "Średnio sesji",
                        "value" => round($data->count() / $incomeByMonth->count(), 1),
                    ],
                    "time" => [
                        "label" => "Średnio godzin",
                        "value" => round($data->sum("duration_h") / $incomeByMonth->count(), 1) . " h",
                    ],
                    "income" => [
                        "label" => "Średnio zarobiono",
                        "value" => round($data->sum("cost") / $incomeByMonth->count(), 2) . " zł",
                    ],
                ],
            ],
        ];

        $sections = [
            [
                "title" => "Przychody w miesiącach",
                "icon" => "calendar-month",
                "id" => "income-by-month",
            ],
            [
                "title" => "Podsumowanie",
                "icon" => "chart-bar",
                "id" => "summary",
            ],
        ];

        return view("pages.stats.index", compact(
            "incomeByMonth",
            "summary",
            "sections",
        ));
    }

    #region range
    public function updateRange(Request $rq)
    {
        $fields = $rq->except("_token");
        foreach ($fields as $name => $value) {
            Setting::find($name)->update(["value" => $value]);
        }
        return back()->with("toast", ["success", "Zaktualizowano zakres"]);
    }
    #endregion

    #region helpers
    protected function fillInBlanks($collection, $mode, $fill)
    {
        $keys = $collection->keys()->sort();

        if ($mode == "month") {
            $years = $keys->map(fn ($key) => substr($key, 0, 4))->unique();
            $first_month = $keys->first();
            $last_month = $keys->last();

            for ($year = $years->first(); $year <= $years->last(); $year++) {
                for ($month = 1; $month <= 12; $month++) {
                    $current_month = implode("-", [$year, Str::padLeft($month, 2, "0")]);
                    if (!$keys->contains($current_month) && $current_month >= $first_month && $current_month <= $last_month) {
                        $collection[$current_month] = [
                            "label" => $current_month,
                            ...$fill,
                        ];
                    }
                }
            }
        }

        $collection = $collection->sortKeys();

        return $collection;
    }
}
