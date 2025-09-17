@props([
    "status"
])

<x-shipyard.app.icon-label-value
    :icon="$status->icon"
    label="Status"
    style="color: {{ $status->color }};"
>
    {{ $status->name }}
</x-shipyard.app.icon-label-value>