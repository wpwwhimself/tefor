@extends("layouts.shipyard.admin")
@section("Lista sesji")

@section("content")

<x-shipyard.app.card
    :title="model('student-sessions')::META['label']"
    :icon="model_icon('student-sessions')"
>
    @forelse ($data as $session)
    <x-sessions.tile :session="$session" />
    @empty
    <div class="ghost">Brak zapisanych sesji.</div>
    @endforelse

    {{ $data->links() }}
</x-shipyard.app.card>

@endsection
