<?php

namespace Modules\Order\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Mail\DailyOrderReportMail;
use Modules\Order\Services\OrderReportService;

class SendDailyOrderReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $email
    ) {}

    public function handle(OrderReportService $service): void
    {
        $report = $service->lastSevenDaysSummary();

        Mail::to($this->email)
            ->send(new DailyOrderReportMail($report));
    }
}
