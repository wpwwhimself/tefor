<x-shipyard.app.card>
    <div class="flex right center middle">
        Wyświetlam statystyki z zakresu od
        <strong class="accent primary">{{ setting("stats_range_from") }}</strong>
        do
        <strong class="accent primary">{{ setting("stats_range_to") }}</strong>

        @php
        $min_year = Carbon\Carbon::parse(App\Models\StudentSession::min("started_at"))->format("Y");
        @endphp
        @for ($year = date("Y"); $year >= $min_year; $year--)
        <x-shipyard.ui.button
            icon="calendar-edit"
            :label="$year"
            :action="route('stats.range.update-quick', [
                'year' => $year,
            ])"
            class="primary"
        />
        @endfor

        <x-shipyard.ui.button
            icon="calendar-edit"
            label="Zmień zakres ręcznie"
            action="none"
            onclick="openModal('update-stats-range', {
                stats_range_from: '{{ setting('stats_range_from') }}',
                stats_range_to: '{{ setting('stats_range_to') }}',
            })"
            class="tertiary"
        />
    </div>
</x-shipyard.app.card>
