<x-mail::message>
Votre demande d'adhésion pour {{ $institution }} a été rejetée pour a raison suivante:
<br>
{{ $raison }}

Cordialement <br />
{{ config('app.name') }}
</x-mail::message>
