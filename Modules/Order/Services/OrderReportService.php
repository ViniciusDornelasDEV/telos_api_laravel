<?php

namespace Modules\Order\Services;

use Carbon\Carbon;
use Modules\Order\Models\Order;

class OrderReportService
{
    public function lastSevenDaysSummary(): array
    {
        $from = Carbon::now()->subDays(7)->startOfDay();

        $orders = Order::where('date', '>=', $from)->get();

        return [
            'period' => [
                'from' => $from->toDateString(),
                'to'   => now()->toDateString(),
            ],
            'total' => $orders->count(),
            'by_status' => $orders
                ->groupBy('status')
                ->map(fn ($group) => $group->count())
                ->toArray(),
        ];
    }
}
