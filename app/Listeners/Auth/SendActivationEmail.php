<?php

namespace App\Listeners\Auth;

use App\Mail\Auth\ActivationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendActivationEmail implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // dd($event->user->generateConfirmationToken());
        Mail::to($event->user)->send(new ActivationEmail($event->user->generateConfirmationToken()));
    }
}
