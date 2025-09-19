@extends("layouts.shipyard.admin")
@section("title", "Kalendarz")

@section("content")

<x-shipyard.app.card
    title="Kalendarz"
    icon="calendar"
>
    <center>
        <iframe
            src="https://calendar.google.com/calendar/embed?src={{ env("GOOGLE_CALENDAR_ID") }}&ctz=Europe%2FWarsaw"
            style="border: 0"
            width="800" height="600"
            frameborder="0"
            scrolling="no"
        ></iframe>
    </center>
</x-shipyard.app.card>

@endsection
