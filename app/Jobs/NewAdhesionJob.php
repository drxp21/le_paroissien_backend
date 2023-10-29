<?php

namespace App\Jobs;

use App\Mail\NewAdhesionMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NewAdhesionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $prenom;
    public $nom;
    public $numero;
    public $emaildemandeur;
    public $denomination;
    public $statut;

    /**
     * Create a new job instance.
     */
    public function __construct($prenom, $nom, $numero, $email, $denomination, $statut)
    {
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->numero = $numero;
        $this->emaildemandeur = $email;
        $this->denomination = $denomination;
        $this->statut = $statut;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach (User::where('role', 'superadmin')->get() as $user) {
            Mail::to($user->email)->send(new NewAdhesionMail($this->prenom, $this->nom, $this->numero, $this->emaildemandeur, $this->denomination, $this->statut));
        }
    }
}
