<?php

namespace App\Jobs;

use App\Mail\ReservationNotify;
use App\Models\Reservations;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class ReservationNotifyJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly Reservations $reservations)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = $this->reservations->email;
        Mail::to($email)->send(new ReservationNotify($this->reservations));
    }
}
