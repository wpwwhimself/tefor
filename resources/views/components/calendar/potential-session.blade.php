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
        @if ($student instanceof \App\Models\Student)
            {!! $student->display_title !!}
        @else
            <x-shipyard.app.h lvl="3" :icon="model_icon('students')" role="card-title">
                {{ $student }}
                @if (!$student_exists)
                <span class="ghost">(uczeń niezapisany)</span>
                @endif
            </x-shipyard.app.h>
        @endif
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

        <x-shipyard.app.icon-label-value
            icon="cash"
            label="Koszt"
        >
            @if ($student instanceof \App\Models\Student)
            {{ $student->calculateCost($durationH) }} zł
            @else
            ?
            @endif
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
        <x-shipyard.ui.button
            :icon="model_icon('students')"
            pop="Edytuj ucznia"
            :action="route('admin.model.edit', ['model' => 'students', 'id' => $student->id])"
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
