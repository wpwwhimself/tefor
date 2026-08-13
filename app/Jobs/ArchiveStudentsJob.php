<?php

namespace App\Jobs;

use App\Models\Student;
use App\Models\StudentStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ArchiveStudentsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $archive_status = StudentStatus::where("index", 99)->first();
        if (!$archive_status) {
            throw new \Exception("Archiwalny status (kolejność = 99) nie istnieje.");
        }

        $students_to_archive = Student::whereDoesntHave("sessions", fn ($q) =>
            $q->whereDate("started_at", ">", now()->subMonths(setting("students_archival_after_months")))
        )
            ->where("student_status_id", "<>", $archive_status->id);

        $students_to_archive->update([
            "student_status_id" => $archive_status->id,
        ]);

        Log::info("Zarchiwizowano " . $students_to_archive->count() . " uczniów.");
    }
}
