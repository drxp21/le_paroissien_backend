<x-mail::message>

    {{ $prenom }} {{ $nom }}, numéro : {{ $numero }}, a fait une demande d'adhésion pour
    {{ $denomination }}({{ $statut }}).
    Veuillez consulter votre tableau de bord.

    Cordialement, 
    {{ config('app.name') }}
</x-mail::message>
