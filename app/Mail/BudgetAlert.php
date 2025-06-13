<?php

namespace App\Mail;

use App\Models\Budget;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BudgetAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $budget;

    public function __construct(Budget $budget)
    {
        $this->budget = $budget;
    }

    public function build()
    {
        return $this->markdown('emails.budget-alert')
            ->subject('Alerte : Dépassement de budget')
            ->with([
                'budget' => $this->budget,
                'category' => $this->budget->category->name,
                'planned' => $this->budget->planned_amount,
                'actual' => $this->budget->actual_amount,
                'percentage' => $this->budget->getProgressPercentage(),
            ]);
    }
} 