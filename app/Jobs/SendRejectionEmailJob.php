<?php

namespace App\Jobs;

use App\Mail\RejectionEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendRejectionEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $emaildemandeur;
    public $raison;
    public $institution;
    /**
     * Create a new job instance.
     */
    public function __construct($emaildemandeur,$raison,$institution)
    {
        $this->emaildemandeur = $emaildemandeur;
        $this->raison = $raison;
        $this->institution = $institution;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->emaildemandeur)->send(new RejectionEmail($this->institution, $this->raison));
    }
}
