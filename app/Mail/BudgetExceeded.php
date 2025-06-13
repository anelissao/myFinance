<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BudgetExceeded extends Mailable
{
    use Queueable, SerializesModels;

    public $budget;
    public $total;

    public function __construct($budget, $total)
    {
        $this->budget = $budget;
        $this->total = $total;
    }

    public function build()
    {
        return $this->subject('Budget Exceeded Alert')
            ->view('emails.budget-exceeded');
    }
}
