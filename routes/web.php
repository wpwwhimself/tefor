<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

if (file_exists(__DIR__.'/Shipyard/shipyard.php')) require __DIR__.'/Shipyard/shipyard.php';

Route::redirect("/", "/calendar/today");

Route::middleware("auth")->group(function () {
    Route::controller(CalendarController::class)->prefix("calendar")->group(function () {
        Route::get("today", "today")->name("calendar.today");
        Route::get("show", "show")->name("calendar.show");

        Route::prefix("sessions")->group(function () {
            Route::get("", "sessions")->name("calendar.sessions");
            Route::get("create", "sessionsCreate")->name("calendar.sessions.create");
        });
    });

    Route::controller(StudentController::class)->prefix("students")->group(function () {
        Route::get("", "list")->name("students.list");
        Route::post("create", "create")->name("students.create");

        Route::prefix("rates")->group(function () {
            Route::post("update", "ratesUpdate")->name("students.rates.update");
        });
    });

    Route::controller(StatsController::class)->prefix("stats")->group(function () {
        Route::get("", "index")->name("stats.index");

        Route::post("/pick-student", "pickStudent")->name("stats.pick-student");
        Route::post("/range/update", "updateRange")->name("stats.range.update");
    });
});
