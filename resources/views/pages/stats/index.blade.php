@extends("layouts.shipyard.admin")
@section("title", ($student) ? "Statystyki ucznia: $student" : "Statystyki")

@section("sidebar")

<div class="card stick-top">
    @foreach ($sections as $section)
    <x-shipyard.ui.button
        :icon="$section['icon']"
        :pop="$section['title']"
        action="#{{ $section['id'] }}"
        class="tertiary"
    />
    @endforeach

    <x-shipyard.app.sidebar-separator />

    <x-shipyard.ui.button
        :icon="model_icon('students')"
        pop="Wybierz ucznia"
        action="none"
        onclick="openModal('stats-for-student', {
            student_id: '{{ request('student') }}',
        })"
        class="primary"
    />
</div>

@endsection

@section("content")

<x-shipyard.app.card
    :title="$sections[0]['title']"
    :icon="$sections[0]['icon']"
    :id="$sections[0]['id']"
>
    <x-stats.range />

    <p class="ghost">Kolorowe liczby oznaczają różnicę pomiędzy aktualnym okresem a tym samym okresem rok wcześniej, tj. {{ Carbon\Carbon::parse(setting("stats_range_from"))->subYear()->format("Y-m-d") }} do {{ Carbon\Carbon::parse(setting("stats_range_to"))->subYear()->format("Y-m-d") }}.</p>

    @foreach ($summary as $s_cat)
    <x-shipyard.app.h lvl="3" :icon="$s_cat['icon']">{{ $s_cat["label"] }}</x-shipyard.app.h>
    @isset ($s_cat["footnote"]) <p class="ghost">{{ $s_cat["footnote"] }}</p> @endisset

    <div class="flex right center">
        @foreach ($s_cat["data"] as $s_data)
        <x-shipyard.stats.tile
            :label="$s_data['label']"
            :value="$s_data['value']"
            :compared-to="$s_data['compared_to']"
        />
        @endforeach
    </div>
    @endforeach
</x-shipyard.app.card>

<x-shipyard.app.card
    :title="$sections[1]['title']"
    :icon="$sections[1]['icon']"
    :id="$sections[1]['id']"
>
    @foreach ($incomeByMonth as $data)
    <x-shipyard.stats.chart.column
        :data="$data"
        :title="substr(current(current($data))['label'], 0, 4)"
    />
    @endforeach
</x-shipyard.app.card>

@endsection
