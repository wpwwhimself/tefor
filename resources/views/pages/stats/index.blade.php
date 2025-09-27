@extends("layouts.shipyard.admin")
@section("title", ($student) ? "Statystyki ucznia: $student" : "Statystyki")

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

    <x-shipyard.app.sidebar-separator />

    <x-shipyard.ui.button
        :icon="model_icon('students')"
        pop="Wybierz ucznia"
        pop-direction="right"
        action="none"
        onclick="openModal('stats-for-student', {
            student_id: '{{ request('student') }}',
        })"
        class="primary"
    />
</div>

@endsection

@section("content")

<x-stats.range />

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
