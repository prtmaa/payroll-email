<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PayrollSlipMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payroll;
    public $periode;

    public function __construct($payroll, $periode)
    {
        $this->payroll = $payroll;
        $this->periode = $periode;
    }

    public function build()
    {
        return $this->from('arunika829@gmail.com', 'Admin Gudang')->subject("Slip Gaji WHL")
            ->markdown('emails.payroll')
            ->with([
                'payroll' => $this->payroll,
                'periode' => $this->periode,
            ]);
    }
}
