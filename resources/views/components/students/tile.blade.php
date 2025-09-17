@props([
    "student"
])

<div role="model-card">
    <div role="top-part">
        <h3 role="card-title">{{ $student->name }}</h3>
    </div>

    <div role="middle-part">
        {!! $student->status !!}
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