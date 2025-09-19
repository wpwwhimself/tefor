@props([
    "session",
])

<div role="model-card">
    <div role="top-part">
        <h3 role="card-title">
            {{ $session->student }}
        </h3>
    </div>

    <div role="middle-part">
        <x-shipyard.app.model.field-value
            :model="$session"
            field="started_at"
        >
            {{ $session->started_at->format("d.m.Y H:i") }}
        </x-shipyard.app.model.field-value>
        <x-shipyard.app.model.field-value
            :model="$session"
            field="duration_h"
        >
            {{ $session->duration_h }} h
        </x-shipyard.app.model.field-value>
        <x-shipyard.app.model.field-value
            :model="$session"
            field="cost"
        >
            {{ $session->cost }} zł
        </x-shipyard.app.model.field-value>
    </div>

    <div role="bottom-part">
        <x-shipyard.ui.button
            :action="route('admin.model.edit', ['model' => 'student-sessions', 'id' => $session->id])"
            icon="pencil"
            pop="Edytuj"
        />
    </div>
</div>
