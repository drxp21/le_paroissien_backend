 <x-mail::message>
     # Introduction
     Nous avons l’honneur de vous confirmer la validation de votre demande d’adhésion. Pour la création de votre profil
     « ADMIN ».
     Voici vos identifiants de connexion :
     Email : {{ $email }}
     Mot de passe : {{ $password }}



     Cordialement,<br>
     {{ config('app.name') }}
 </x-mail::message>
