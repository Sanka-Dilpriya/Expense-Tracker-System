<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MonthlyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function build(): static
    {
        return $this->subject('Monthly Expense & Income Report - ' . $this->reportData['month'])
            ->view('reports.email')
            ->with('reportData', $this->reportData);
    }
}
