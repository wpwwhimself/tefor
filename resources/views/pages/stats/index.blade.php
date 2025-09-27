@extends("layouts.shipyard.admin")
@section("title", "Statystyki")

@section("sidebar")

<div class="card stick-top">
    @foreach ($sections as $section)
    <x-shipyard.ui.button
        :icon="$section['icon']"
        :pop="$section['title']"
        pop-direction="right"
        action="#{{ $section['id'] }}"
        class="tertiary"
    />
    @endforeach
</div>

@endsection

@section("content")

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

<x-shipyard.app.card
    :title="$sections[0]['title']"
    :icon="$sections[0]['icon']"
    :id="$sections[0]['id']"
>
    <x-shipyard.stats.chart.column
        :data="$incomeByMonth"
    />
</x-shipyard.app.card>

<x-shipyard.app.card
    :title="$sections[1]['title']"
    :icon="$sections[1]['icon']"
    :id="$sections[1]['id']"
>
    @foreach ($summary as $s_cat)
    <x-shipyard.app.h lvl="3" :icon="$s_cat['icon']">{{ $s_cat["label"] }}</x-shipyard.app.h>

    <div class="flex right center">
        @foreach ($s_cat["data"] as $s_data)
        <x-shipyard.stats.tile :label="$s_data['label']">
            {{ $s_data["value"] }}
        </x-shipyard.stats.tile>
        @endforeach
    </div>
    @endforeach
</x-shipyard.app.card>

@endsection
