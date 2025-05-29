<?php

namespace App\Jobs;

use App\Models\RekapGaji;
use App\Mail\PayrollSlipMail;
use App\Models\Periode;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPayrollEmail implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payroll;
    protected $periode;  // jangan lupa deklarasi properti ini

    public function __construct($payroll, $periode)
    {
        $this->payroll = $payroll;
        $this->periode = $periode;
    }

    public function handle()
    {
        // contoh penggunaan $this->periode di sini
        Mail::to($this->payroll->email)->send(new PayrollSlipMail($this->payroll, $this->periode));
    }
}
