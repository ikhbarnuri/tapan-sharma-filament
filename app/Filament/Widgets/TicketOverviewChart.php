<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TicketOverviewChart extends ChartWidget
{
    protected static ?string $heading = 'Ticket Overview';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '200px';

    public ?string $filter = 'week';

    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last Week',
            'month' => 'Last Month',
            'year' => 'This Year',
        ];
    }

    protected function getData(): array
    {
        $start = null;
        $end = null;
        $perData = null;

        switch ($this->filter) {
            case 'week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                $perData = 'perDay';
                break;
            case 'month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $perData = 'perMonth';
                break;
            case 'year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $perData = 'perYear';
                break;
            default:
                break;
        }

        $data = Trend::model(Ticket::class)
            ->between(
                start: $start,
                end: $end,
            )
            ->$perData()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Ticket data',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
