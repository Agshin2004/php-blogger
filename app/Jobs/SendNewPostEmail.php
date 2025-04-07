<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\NewPostEmail;

class SendNewPostEmail implements ShouldQueue
{
    use Queueable;

    private $data;
    
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->data['sendTo'])->send(new NewPostEmail([
           'username' => $this->data['username'],
           'postTitle' => $this->data['postTitle']
        ]));
    }
}
