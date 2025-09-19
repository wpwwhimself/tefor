@props([
    "student"
])

<div role="model-card">
    <div role="top-part">
        <h3 role="card-title">{{ $student->name }}</h3>
    </div>

    <div role="middle-part">
        {!! $student->status !!}

        <x-shipyard.app.icon-label-value
            icon="cash"
            label="Stawka za godzinę/mniej niż godzinę"
        >
            {{ $student->default_rate }} / {{ $student->default_rate_below_hour }} zł
        </x-shipyard.app.icon-label-value>
    </div>

    <div role="bottom-part">
        <div role="card-actions">
            <x-shipyard.ui.button
                :action="route('admin.model.edit', ['model' => 'students', 'id' => $student->id])"
                icon="pencil"
                pop="Edytuj"
            />
        </div>
    </div>
</div>
