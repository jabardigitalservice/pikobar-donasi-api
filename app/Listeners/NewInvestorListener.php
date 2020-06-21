<?php

namespace App\Listeners;

use App\Events\NewInvestorEvent;
use App\Jobs\SendInvestorEmail;
use App\Libraries\ConstantParser;
use App\Models\Constants;
use Illuminate\Support\Facades\Log;

class NewInvestorListener
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
    public function handle(NewInvestorEvent $event)
    {
        $donateStatus = ConstantParser::searchBySlug($event->investor->donate_status,
            Constants::INVESTOR_STATUS);
        if ($donateStatus['slug'] === 'verified') {
            SendInvestorEmail::dispatch($event);
        }
        Log::notice("NEW INVESTOR IS COMMING" . json_encode($event));
    }
}
