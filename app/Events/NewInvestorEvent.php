<?php

namespace App\Events;

use App\Models\Investor;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewInvestorEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $investor;

    /**
     * Create a new event instance.
     *
     * NewInvestorEvent constructor.
     * @param Investor $investor
     */
    public function __construct(Investor $investor)
    {
        //
        $this->investor = $investor;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('investor-new');
    }
}
