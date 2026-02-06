<?php

use Modules\Order\Jobs\SendDailyOrderReportJob;
use Illuminate\Console\Scheduling\Schedule;

protected function schedule(Schedule $schedule)
{
    $schedule->job(
        new SendDailyOrderReportJob('vinicius.s.dornelas@gmail.com')
    )->dailyAt('08:00');
}
