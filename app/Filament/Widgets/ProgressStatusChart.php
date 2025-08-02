<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use App\Models\JobOrder;
use Log;

class ProgressStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Job Orders';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '200px';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = $this->getProgressPerMonth();

        return [
            'datasets' => [
                [
                    'label' => 'Job Order Created',
                    'data' => $data['jobOrdersPerMonth']
                ],
            ],
            'labels' => $data['months']

        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getProgressPerMonth() {
        
        $now = Carbon::now();
        $jobOrdersPerMonth = [];

        $months = collect(range(1,12))->map(function($month) use ($now, $jobOrdersPerMonth){
            return $now->month($month)->format('M');

        })->toArray();

        $jobOrdersPerMonth = collect(range(1,12))->map(function($month) use ($now, $jobOrdersPerMonth){
            $count = JobOrder::whereMonth('created_at', Carbon::parse($now->month($month)->format('Y-m')))->count();
            return $count;
        })->toArray();

        return [
            'jobOrdersPerMonth' => $jobOrdersPerMonth,
            'months' => $months
        ];
    }
}
