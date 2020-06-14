<?php

namespace App\Mail;

use App\Models\Investor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvestorThanks extends Mailable
{
    use Queueable, SerializesModels;

    protected $investor;

    /**
     * Create a new message instance.
     *
     * @param Investor $investor
     * @return void
     */
    public function __construct(Investor $investor)
    {
        $this->investor = $investor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.investor.thanks')
            ->subject("Terima kasih")
            ->with([
                'email' => $this->investor->email,
            ]);
    }
}
