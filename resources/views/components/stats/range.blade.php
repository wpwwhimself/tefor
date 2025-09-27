<x-shipyard.app.card>
    <div class="flex right center middle">
        Wyświetlam statystyki z zakresu od
        <strong class="accent primary">{{ setting("stats_range_from") }}</strong>
        do
        <strong class="accent primary">{{ setting("stats_range_to") }}</strong>

        <x-shipyard.ui.button
            icon="calendar-edit"
            label="Zmień zakres"
            action="none"
            onclick="openModal('update-stats-range', {
                stats_range_from: '{{ setting('stats_range_from') }}',
                stats_range_to: '{{ setting('stats_range_to') }}',
            })"
            class="primary"
        />
    </div>
</x-shipyard.app.card>
