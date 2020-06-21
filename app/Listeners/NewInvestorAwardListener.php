<?php

namespace App\Listeners;

use App\Events\NewInvestorAwardEvent;
use App\Jobs\SendInvestorEmail;
use App\Libraries\ConstantParser;
use App\Models\Constants;
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
        $donateStatus = ConstantParser::searchBySlug($event->investor->donate_status,
            Constants::INVESTOR_STATUS);
        if ($donateStatus['slug'] === 'verified') {
            SendInvestorEmail::dispatch($event);
        }
        Log::notice("INVESTOR AWARD SEND" . json_encode($event));
    }
}