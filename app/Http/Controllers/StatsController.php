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

        $min_year = Carbon::parse(StudentSession::min("started_at"))->format("Y");
        $incomeByMonth = [];
        $data = null;
        for ($year = date("Y"); $year >= $min_year; $year--) {
            $data = StudentSession::orderBy("started_at")
                ->where("started_at", ">=", Carbon::parse("$year-01-01"))
                ->where("started_at", "<=", Carbon::parse("$year-12-31"));
            if ($student) {
                $data = $data->where("student_id", $student->id);
            }

            $data = $data->get();

            $data = $data->groupBy(fn ($ss) => $ss->started_at->format("Y-m"))
                ->map(fn ($sss, $month) => [
                    "label" => $month,
                    "value" => $sss->sum("cost"),
                    "value_label" => $sss->sum("cost")." zł, ".$sss->sum("duration_h")." h",
                ]);
            $data = $this->fillInBlanks($data, "month", [
                "value" => 0,
                "value_label" => "0 zł, 0 h",
            ]);

            $incomeByMonth[] = $data;
        }
        unset($data);

        foreach ([
            ["highlightedIncome", "hiData", 0],
            ["highlightedIncomeYearBack", "hiybData", 1],
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
                        "value" => $hiData->count(),
                        "compared_to" => $hiybData->count(),
                    ],
                    "time" => [
                        "label" => "Łącznie godzin",
                        "value" => $hiData->sum("duration_h"),
                        "compared_to" => $hiybData->sum("duration_h"),
                    ],
                    "income" => [
                        "label" => "Łącznie zarobiono [zł]",
                        "value" => $hiData->sum("cost"),
                        "compared_to" => $hiybData->sum("cost"),
                    ],
                ]
            ],
            "avgs" => [
                "label" => "Średnie miesięczne",
                "footnote" => "Średnie są liczone jako [suma wartości w wybranym okresie]/[liczba miesięcy w wybranym okresie]",
                "icon" => "counter",
                "data" => [
                    "sessions" => [
                        "label" => "Średnio sesji",
                        "value" => round($hiData->count() / max($highlightedIncome->count(), 1), 1),
                        "compared_to" => round($hiybData->count() / max($highlightedIncomeYearBack->count(), 1), 1),
                    ],
                    "time" => [
                        "label" => "Średnio godzin",
                        "value" => round($hiData->sum("duration_h") / max($highlightedIncome->count(), 1), 1),
                        "compared_to" => round($hiybData->sum("duration_h") / max($highlightedIncomeYearBack->count(), 1), 1),
                    ],
                    "income" => [
                        "label" => "Średnio zarobiono [zł]",
                        "value" => round($hiData->sum("cost") / max($highlightedIncome->count(), 1), 2),
                        "compared_to" => round($hiybData->sum("cost") / max($highlightedIncomeYearBack->count(), 1), 2),
                    ],
                ],
            ],
        ];

        $sections = [
            [
                "title" => "Podsumowanie",
                "icon" => "chart-bar",
                "id" => "summary",
            ],
            [
                "title" => "Wszystkie przychody w miesiącach",
                "icon" => "calendar-month",
                "id" => "income-by-month",
            ],
        ];

        return view("pages.stats.index", compact(
            "incomeByMonth",
            "highlightedIncome",
            "highlightedIncomeYearBack",
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
            "stats_range_from" => max("$rq->year-01-01", Carbon::parse(StudentSession::min("started_at"))->format("Y-m-d")),
            "stats_range_to" => min("$rq->year-12-31", date("Y-m-d")),
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
