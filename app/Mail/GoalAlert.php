<?php

namespace App\Mail;

use App\Models\FinancialGoal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GoalAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $goal;

    public function __construct(FinancialGoal $goal)
    {
        $this->goal = $goal;
    }

    public function build()
    {
        return $this->markdown('emails.goal-alert')
            ->subject('Alerte : Dépassement d\'objectif financier')
            ->with([
                'goal' => $this->goal,
            ]);
    }
}
