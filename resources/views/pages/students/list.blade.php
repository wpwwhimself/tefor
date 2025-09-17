@extends("layouts.shipyard.admin")
@section("title", model("students")::META["label"])

@section("content")

<x-shipyard.app.card
    :title="model('students')::META['label']"
    :icon="model('students')::META['icon']"
>
    <x-slot:actions>
        <x-shipyard.app.icon-label-value
            icon="counter"
            label="Liczba wpisów"
        >
            {{ $data->count() }}
        </x-shipyard.app.icon-label-value>

        <x-shipyard.ui.button
            :action="route('admin.model.edit', ['model' => 'students'])"
            icon="plus"
            label="Dodaj"
        />
    </x-slot:actions>

    @foreach ($data as $student)
    <x-students.tile :student="$student" />
    @endforeach
</x-shipyard.app.card>

@endsection
