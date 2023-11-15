<x-mail::message>


{{ $message }}

<x-mail::button :url="$url">
Visiter
</x-mail::button>

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
