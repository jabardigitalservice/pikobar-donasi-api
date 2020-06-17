<?php

namespace App\Listeners;

use App\Events\NewInvestorAwardEvent;
use App\Jobs\SendInvestorEmail;
use Illuminate\Support\Facades\Log;

class NewInvestorAwardListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle(NewInvestorAwardEvent $event)
    {
        SendInvestorEmail::dispatch($event);
        Log::notice("NEW INVESTOR IS COMMING" . json_encode($event));
    }
}