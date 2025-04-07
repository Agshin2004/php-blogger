<?php

namespace App\Jobs;

use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmail implements ShouldQueue
{
    use Queueable;

    private $userData;

    /**
     * Create a new job instance.
     */
    public function __construct($userData)
    {
        $this->userData = $userData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to('example@mail.com')->send(new WelcomeEmail([
            'username' => $this->userData['username']
        ]));
    }
}
