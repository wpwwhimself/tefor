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
        Student::create($rq->all());
        return back()->with("toast", ["success", "Utworzono ucznia"]);
    }
}
