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
        <x-shipyard.ui.button
            icon="cash-edit"
            pop="Masowa zmiana stawek"
            action="none"
            onclick="openModal('update-default-rates')"
        />
    </x-slot:actions>

    @foreach ($data as $student)
    <x-shipyard.app.model.tile :model="$student">
        <x-slot:actions>
            <x-shipyard.ui.button
                :action="route('stats.index', ['student' => $student])"
                icon="chart-bar"
                label="Statystyki"
            />
            <x-shipyard.ui.button
                :action="route('admin.model.edit', ['model' => 'students', 'id' => $student->id])"
                icon="pencil"
                pop="Edytuj"
            />
        </x-slot:actions>
    </x-shipyard.app.model.tile>
    @endforeach
</x-shipyard.app.card>

@endsection
