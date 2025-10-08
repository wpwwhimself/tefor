<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function list()
    {
        $data = Student::join("student_statuses", "student_statuses.id", "=", "students.student_status_id")
            ->orderBy("student_statuses.index")
            ->orderBy("name")
            ->select("students.*")
            ->get();

        return view("pages.students.list", compact(
            "data",
        ));
    }

    public function create(Request $rq)
    {
        $data = $rq->except("_token");
        $data["default_rate"] = $data["cash_for_60_min"];
        $data["default_rate_below_hour"] = round($data["cash_for_45_min"] * 60 / 45, 2);

        Student::create($data);
        return back()->with("toast", ["success", "Utworzono ucznia"]);
    }

    public function ratesUpdate(Request $rq)
    {
        $data = $rq->except("_token");
        $data["default_rate"] = $data["cash_for_60_min"];
        $data["default_rate_below_hour"] = round($data["cash_for_45_min"] * 60 / 45, 2);

        Student::whereRaw(true)->update($data);
        return back()->with("toast", ["success", "Stawki zaktualizowane"]);
    }
}
