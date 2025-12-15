<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CalendarController extends Controller
{
    #region calendar
    public function today()
    {
        $eventLower = Carbon::today()->subMonths(3);
        $eventUpper = Carbon::today()->endOfDay();

        $calendarEvents = collect();
        $calendarError = null;

        try {
            $calendarEvents = Http::withQueryParameters([
                "key" => env("GOOGLE_API_KEY"),
                "timeMin" => $eventLower->toRfc3339String(),
                "timeMax" => $eventUpper->toRfc3339String(),
                "singleEvents" => "true",
                "orderBy" => "startTime",
            ])
                ->get("https://www.googleapis.com/calendar/v3/calendars/".env("GOOGLE_CALENDAR_ID")."/events")
                ->collect();

            if ($calendarEvents->has("error")) {
                throw new \Exception(implode(" ", [
                    $calendarEvents["error"]["code"],
                    $calendarEvents["error"]["message"],
                ]));
            }

            $calendarEvents = collect($calendarEvents["items"])
                ->map(fn ($ev) => [
                    "student" => Student::where("nickname", $ev["summary"])->first() ?? $ev["summary"],
                    "started_at" => Carbon::parse($ev["start"]["dateTime"]),
                    "duration_h" => Carbon::parse($ev["start"]["dateTime"])->diffInHours(Carbon::parse($ev["end"]["dateTime"])),
                ])
                ->filter(fn ($ev) => StudentSession::whereRaw("substr(started_at, 1, 10) = ?", $ev["started_at"]->format("Y-m-d"))
                    ->where("duration_h", $ev["duration_h"])
                    ->where("student_id", gettype($ev["student"]) === "object" ? $ev["student"]->id : null)
                    ->exists() === false
                );
        } catch (\Throwable $th) {
            $calendarEvents = null;
            $calendarError = $th->getMessage();
        }

        $sessionsToday = StudentSession::whereBetween("started_at", [
            Carbon::today()->startOfDay(),
            Carbon::today()->endOfDay(),
        ])
            ->get();

        return view("pages.calendar.today", compact(
            "calendarEvents",
            "calendarError",
            "sessionsToday",
        ));
    }

    public function show()
    {
        return view("pages.calendar.show");
    }
    #endregion

    #region sessions
    public function sessions()
    {
        $data = StudentSession::orderByDesc("started_at")
            ->paginate(25);

        return view("pages.calendar.sessions", compact(
            "data",
        ));
    }

    public function sessionsCreate(Request $rq)
    {
        StudentSession::create([
            ...$rq->all(),
            "cost" => Student::find($rq->student_id)->calculateCost($rq->duration_h),
        ]);
        return back()->with("toast", ["success", "Utworzono sesję"]);
    }
    #endregion
}
