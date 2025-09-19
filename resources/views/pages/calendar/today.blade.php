@extends("layouts.shipyard.admin")
@section("title", "Dzisiaj")

@section("content")

<x-shipyard.app.card
    title="Sesje zaplanowane na dzisiaj"
    icon="calendar"
>
    <x-slot:actions>
        <x-shipyard.ui.button
            icon="calendar"
            label="Kalendarz"
            :action="route('calendar.show')"
        />
    </x-slot:actions>

    <p>
        Poniższa lista wyświetla wydarzenia z kalendarza z ostatniego miesiąca,
        które nie mają swojego pokrycia z zapisanymi sesjami.
    </p>

    @forelse ($calendarEvents as $event)
    <x-calendar.potential-session
        :student="$event['student']"
        :started-at="$event['started_at']"
        :duration-h="$event['duration_h']"
    />
    @empty
    <p class="ghost">Wolne na dzisiaj!</p>
    @endforelse
</x-shipyard.app.card>

<x-shipyard.app.card
    title="Dzisiejsze sesje"
    :icon="model_icon('student-sessions')"
>
    <x-slot:actions>
        <x-shipyard.app.icon-label-value
            icon="timer"
            label="Godzin łącznie"
        >
            {{ $sessionsToday->sum("duration_h") }} h
        </x-shipyard.app.icon-label-value>

        <x-shipyard.app.icon-label-value
            icon="cash"
            label="Zarobiono łącznie"
        >
            {{ $sessionsToday->sum("cost") }} zł
        </x-shipyard.app.icon-label-value>
    </x-slot:actions>

    @forelse ($sessionsToday as $session)
    <x-sessions.tile :session="$session" />
    @empty
    <p class="ghost">Brak zapisanych sesji na dzisiaj.</p>
    @endforelse
</x-shipyard.app.card>

@endsection
