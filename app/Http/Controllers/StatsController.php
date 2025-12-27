<?php

namespace App\Http\Controllers;

use App\Models\Shipyard\Setting;
use App\Models\Student;
use App\Models\StudentSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StatsController extends Controller
{
    public function index()
    {
        $student = Student::find(request("student"));

        foreach ([
            ["incomeByMonth", "ibmData", 0],
            ["incomeByMonthYearBack", "ibmybData", 1],
        ] as [$var, $datavar, $offset]) {
            $$datavar = StudentSession::orderBy("started_at")
                ->where("started_at", ">=", Carbon::parse(setting("stats_range_from"))->subYears($offset))
                ->where("started_at", "<=", Carbon::parse(setting("stats_range_to"))->subYears($offset));
            if ($student) {
                $$datavar = $$datavar->where("student_id", $student->id);
            }

            $$datavar = $$datavar->get();

            $$var = $$datavar->groupBy(fn ($ss) => $ss->started_at->format("Y-m"))
                ->map(fn ($sss, $month) => [
                    "label" => $month,
                    "value" => $sss->sum("cost"),
                    "value_label" => $sss->sum("cost")." zł, ".$sss->sum("duration_h")." h",
                ]);
            $$var = $this->fillInBlanks($$var, "month", [
                "value" => 0,
                "value_label" => "0 zł, 0 h",
            ]);
        }

        $summary = [
            "sums" => [
                "label" => "Sumy",
                "icon" => "counter",
                "data" => [
                    "sessions" => [
                        "label" => "Łącznie sesji",
                        "value" => $ibmData->count(),
                        "compared_to" => $ibmybData->count(),
                    ],
                    "time" => [
                        "label" => "Łącznie godzin",
                        "value" => $ibmData->sum("duration_h"),
                        "compared_to" => $ibmybData->sum("duration_h"),
                    ],
                    "income" => [
                        "label" => "Łącznie zarobiono [zł]",
                        "value" => $ibmData->sum("cost"),
                        "compared_to" => $ibmybData->sum("cost"),
                    ],
                ]
            ],
            "avgs" => [
                "label" => "Średnie",
                "icon" => "counter",
                "data" => [
                    "sessions" => [
                        "label" => "Średnio sesji",
                        "value" => round($ibmData->count() / max($incomeByMonth->count(), 1), 1),
                        "compared_to" => round($ibmybData->count() / max($incomeByMonthYearBack->count(), 1), 1),
                    ],
                    "time" => [
                        "label" => "Średnio godzin",
                        "value" => round($ibmData->sum("duration_h") / max($incomeByMonth->count(), 1), 1),
                        "compared_to" => round($ibmybData->sum("duration_h") / max($incomeByMonthYearBack->count(), 1), 1),
                    ],
                    "income" => [
                        "label" => "Średnio zarobiono [zł]",
                        "value" => round($ibmData->sum("cost") / max($incomeByMonth->count(), 1), 2),
                        "compared_to" => round($ibmybData->sum("cost") / max($incomeByMonthYearBack->count(), 1), 2),
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
            "incomeByMonthYearBack",
            "summary",
            "sections",
            "student",
        ));
    }

    #region filters
    private function _updateRange($fields)
    {
        foreach ($fields as $name => $value) {
            Setting::find($name)->update(["value" => $value]);
        };
    }

    public function updateRange(Request $rq)
    {
        $fields = $rq->except("_token");
        $this->_updateRange($fields);
        return back()->with("toast", ["success", "Zaktualizowano zakres"]);
    }

    public function updateRangeQuick(Request $rq)
    {
        $fields = [
            "stats_range_from" => "$rq->year-01-01",
            "stats_range_to" => "$rq->year-12-31",
        ];
        $this->_updateRange($fields);
        return back()->with("toast", ["success", "Zaktualizowano zakres"]);
    }

    public function pickStudent(Request $rq)
    {
        return redirect()->route("stats.index", ["student" => $rq->student_id])->with("toast", ["success", "Filtry zmienione"]);
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
