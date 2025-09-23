@extends("layouts.shipyard.admin")
@section("Lista sesji")

@section("content")

<x-shipyard.app.card
    :title="model('student-sessions')::META['label']"
    :icon="model_icon('student-sessions')"
>
    @forelse ($data as $session)
    <x-shipyard.app.model.tile :model="$session">
        <x-shipyard.ui.button
            :action="route('admin.model.edit', ['model' => 'student-sessions', 'id' => $session->id])"
            icon="pencil"
            pop="Edytuj"
        />
    </x-shipyard.app.model.tile>
    @empty
    <div class="ghost">Brak zapisanych sesji.</div>
    @endforelse

    {{ $data->links("components.shipyard.pagination.default") }}
</x-shipyard.app.card>

@endsection
