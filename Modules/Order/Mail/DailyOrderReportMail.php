<?php

namespace Modules\Order\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyOrderReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $report
    ) {}

    public function build()
    {
        return $this
            ->subject('Relatório diário de pedidos')
            ->view('order::emails.daily-report');
    }
}
