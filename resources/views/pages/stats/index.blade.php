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

<x-shipyard.app.card
    :title="$sections[0]['title']"
    :icon="$sections[0]['icon']"
    :id="$sections[0]['id']"
>
    <x-shipyard.stats.chart.column
        :data="$lastYearIncomeByMonth"
    />
</x-shipyard.app.card>

@endsection
