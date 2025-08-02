<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\JobOrder;
use App\Models\JobOrderStatusType;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Log;

class JobOrderStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Job Order by Status';

    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '400px';

    public ?string $filter = 'today';

    protected function getData(): array
    {
        $jobOrderStatusTypesName = $this->getJobOrderStatusesName();
        $jobOrderStatusTypesColor = $this->getJobOrderStatusesColor();

        $activeFilter = $this->filter;

        return [
            'datasets' => [
                [
                    'label' => 'Job Order Created',
                    'data' => $this->getJobOrdersByStatus(),
                    'backgroundColor' => $jobOrderStatusTypesColor,
                    'borderWidth' => 0
                ],
            ],
            'labels' => $jobOrderStatusTypesName
        ];
    }

    private function getJobOrderStatusesName() {
        $statuses = JobOrderStatusType::query()->select('name')->orderBy('id', 'asc')->get();
        return $statuses->map(function ($status) {
            return $status->name;
        });
    }

    private function getJobOrderStatusesColor() {
        $statuses = JobOrderStatusType::query()->select('color')->orderBy('id', 'asc')->get();
        return $statuses->map(function ($status) {
            return $status->color;
        });
    }

    private function getJobOrdersByStatus() {

        $query = JobOrder::query();

        switch ($this->filter) {
            case "today":
                $query->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]);
            break;
            case "week":
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            break;
            case "month": 
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            break;
            case "year":
                $query->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
            break;
        }

        $jobOrders = $query->get();
        $statuses = JobOrderStatusType::orderBy('id', 'asc')->get();

        $jobOrderCounts = [];

        foreach ($statuses as $status) {
            $jobOrderCounts[] = $this->getJobOrderStatusCountByJobOrder($jobOrders, $status);
        }

        return $jobOrderCounts;
    }

    private function getJobOrderStatusCountByJobOrder($jobOrders, $status) {
        return $jobOrders->filter(function ($jobOrder) use ($status) {
            return $jobOrder->job_order_status_type_id == $status->id;
        })->count();
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getFilters(): array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    protected function getOptions(): array|\Filament\Support\RawJs|null
    {
        return [
            'scales' => [
                'x' => [
                    'display' => false,
                ],
                'y' => [
                    'display' => false,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }
    
}
