<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookingsChart extends ChartWidget
{
    protected ?string $heading = 'Bookings per Day (Last 7 Days)';
    protected static ?int $sort = 3;
    // protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $dates = collect(range(0, 6))->map(function ($i) {
            return now()->subDays(6 - $i)->format('Y-m-d');
        });
        $bookings = Booking::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(7)->startOfDay())
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();
        $data = $dates->map(function ($date) use ($bookings) {
            return $bookings[$date] ?? 0;
        });

        return [
            'datasets' => [
                [
                    'label' => 'Bookings',
                    'data' => $data,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $dates->map(fn($date) => Carbon::parse($date)->format('M d')),
        ];
    }
}
