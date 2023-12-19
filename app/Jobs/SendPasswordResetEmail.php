<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;

class SendPasswordResetEmail
{
    use Dispatchable;

    protected $emailToken;
    protected $userEmail;

    /**
     * Create a new job instance.
     */
    public function __construct($emailToken, $userEmail)
    {
        $this->emailToken = $emailToken;
        $this->userEmail = $userEmail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $emailToken = $this->emailToken;
        $userEmail = $this->userEmail;

        // Send the email
        Mail::to($userEmail)->send(new PasswordReset($emailToken)); 
    }
}
