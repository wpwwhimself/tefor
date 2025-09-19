@props([
    "student",
    "startedAt",
    "durationH",
])

@php
$student_exists = gettype($student) === "object";
@endphp

<div role="model-card">
    <div role="top-part">
        <h3 role="card-title">
            {{ $student }}
            @if (!$student_exists)
            <span class="ghost">(uczeń niezapisany)</span>
            @endif
        </h3>
    </div>

    <div role="middle-part">
        <x-shipyard.app.icon-label-value
            icon="calendar"
            label="Data"
        >
            {{ $startedAt->format("d.m.Y H:i") }}
        </x-shipyard.app.icon-label-value>

        <x-shipyard.app.icon-label-value
            icon="timer"
            label="Czas trwania"
        >
            {{ $durationH }} h
        </x-shipyard.app.icon-label-value>
    </div>

    <div role="bottom-part">
        @if ($student_exists)
        <x-shipyard.ui.button
            icon="check"
            label="Zapisz"
            :action="route('calendar.sessions.create', [
                'student_id' => $student,
                'started_at' => $startedAt->format('Y-m-d H:i'),
                'duration_h' => $durationH,
            ])"
            class="primary"
        />
        @else
        <x-shipyard.ui.button
            icon="plus"
            label="Utwórz ucznia"
            action="none"
            onclick="openModal('create-student', {
                nickname: '{{ $student }}',
            })"
            class="tertiary"
        />
        @endif
    </div>
</div>
