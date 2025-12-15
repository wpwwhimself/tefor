@extends("layouts.shipyard.admin")
@section("title", "Dzisiaj")

@section("content")

<x-shipyard.app.section
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
    <p>
        Jeśli coś się zmieniło w tej liście, <span class="accent primary">popraw wydarzenia w kalendarzu i odśwież tę stronę</span>.
    </p>

    @if ($calendarEvents === null)
    <p class="accent danger">Nie udało się połączyć z kalendarzem, a przez to ustalić, jakie są zaplanowane sesje. Daj mi znać, że coś jest nie tak.</p>
    <script>console.error(`{{ $calendarError }}`)</script>
    @else
    @forelse ($calendarEvents as $event)
    <x-calendar.potential-session
        :student="$event['student']"
        :started-at="$event['started_at']"
        :duration-h="$event['duration_h']"
    />
    @empty
    <p class="ghost">Wolne na dzisiaj!</p>
    @endforelse
    @endif
</x-shipyard.app.section>

<x-shipyard.app.section
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
    <x-shipyard.app.model.tile :model="$session">
        <x-shipyard.ui.button
            :action="route('admin.model.edit', ['model' => 'student-sessions', 'id' => $session->id])"
            icon="pencil"
            pop="Edytuj"
        />
    </x-shipyard.app.model.tile>
    @empty
    <p class="ghost">Brak zapisanych sesji na dzisiaj.</p>
    @endforelse
</x-shipyard.app.section>

@endsection
