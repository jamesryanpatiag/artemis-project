<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use App\Models\Customer;
use App\Models\JobOrder;
use Carbon\Carbon;
use Log;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected static ?int $sort = 1;

    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $thisMonth = Carbon::now()->month;
        $lastMonth = Carbon::now()->subMonth(1)->month;

        //condition to check if customer increased from the last month
        $thisMonthCustomers = $this->getCustomerCountPerMonth($thisMonth);
        $lastMonthcustomers = $this->getCustomerCountPerMonth($lastMonth);

        $customerIndicator = 'info';
        $customerIconIndicator = 'heroicon-o-bars-2';
        if ($thisMonthCustomers > $lastMonthcustomers) {
            $customerIndicator = 'success';
            $customerIconIndicator = 'heroicon-o-chevron-double-up';
        }
        if ($thisMonthCustomers < $lastMonthcustomers) {
            $customerIndicator = 'danger';
            $customerIconIndicator = 'heroicon-o-chevron-double-down';
        }

        //condition to check if job order increased from the last month
        $thisMonthJobOrders = $this->getJobOrderCountPerMonth($thisMonth);
        $lastMonthJobOrders = $this->getJobOrderCountPerMonth($lastMonth);

        $jobOrderIndicator = 'info';
        $jobOrderIconIndicator = 'heroicon-o-bars-2';
        if ($thisMonthJobOrders > $lastMonthJobOrders) {
            $jobOrderIndicator = 'success';
            $jobOrderIconIndicator = 'heroicon-o-chevron-double-up';
        }
        if ($thisMonthJobOrders < $lastMonthJobOrders) {
            $jobOrderIndicator = 'danger';
            $jobOrderIconIndicator = 'heroicon-o-chevron-double-down';
        }

        //condition to check if completed job order increased from the last month
        $thisMonthCompletedJobOrders = $this->countOrderByStatusAndMonth($thisMonth, 'Completed');
        $lastMonthCompletedJobOrders = $this->countOrderByStatusAndMonth($lastMonth, 'Completed');

        $completedJobOrderIndicator = 'info';
        $completedJobOrderIconIndicator = 'heroicon-o-bars-2';
        if ($thisMonthCompletedJobOrders > $lastMonthCompletedJobOrders) {
            $completedJobOrderIndicator = 'success';
            $completedJobOrderIconIndicator = 'heroicon-o-chevron-double-up';
        }
        if ($thisMonthCompletedJobOrders < $lastMonthCompletedJobOrders) {
            $completedJobOrderIndicator = 'danger';
            $completedJobOrderIconIndicator = 'heroicon-o-chevron-double-down';
        }



        return [
            Stat::make('Customers', Customer::count())
                ->icon('heroicon-m-users')
                ->iconBackgroundColor('info')
                ->color('info')
                ->iconPosition('start')
                ->chartColor('info')
                ->description('Total Customers registered')
                ->descriptionIcon('heroicon-o-bars-3', 'before')
                ->descriptionColor('info')
                ->iconColor('info'),
            Stat::make('Job Orders', JobOrder::count())
                ->iconBackgroundColor('info')
                ->icon('heroicon-o-document')
                ->iconPosition('start')
                ->description('Total Job Orders created')
                ->descriptionIcon('heroicon-o-bars-3', 'before')
                ->descriptionColor('info')
                ->iconColor('info'),
            Stat::make('Completed Job Orders', $this->countOrderByStatus('Completed'))
                ->iconColor('success')
                ->icon('heroicon-o-check-badge')
                ->iconBackgroundColor('success')
                ->iconPosition('start')
                ->description('Total Completed Job Orders')
                ->descriptionIcon('heroicon-o-bars-3', 'before')
                ->descriptionColor('info'),
            Stat::make('New Customers', Customer::whereMonth('created_at', Carbon::now()->month)->count())
                ->icon('heroicon-o-users')
                ->iconBackgroundColor($customerIndicator)
                ->iconPosition('start')
                ->description("This month's new customers")
                ->descriptionIcon($customerIconIndicator, 'before')
                ->descriptionColor($customerIndicator)
                ->iconColor($customerIndicator),
            Stat::make('New Job Orders', JobOrder::whereMonth('created_at', Carbon::now()->month)->count())
                ->iconBackgroundColor($jobOrderIndicator)
                ->icon('heroicon-o-document')
                ->iconBackgroundColor($jobOrderIndicator)
                ->iconPosition('start')
                ->description("This month's new job orders")
                ->descriptionIcon($jobOrderIconIndicator, 'before')
                ->descriptionColor($jobOrderIndicator)
                ->iconColor($jobOrderIndicator),
            Stat::make('New Completed Job Orders', $this->countOrderByStatusAndMonth(Carbon::now()->month, 'Completed'))
                ->iconBackgroundColor($completedJobOrderIndicator)
                ->icon('heroicon-o-check-badge')
                ->iconBackgroundColor($completedJobOrderIndicator)
                ->iconPosition('start')
                ->description("This month's new completed job orders")
                ->descriptionIcon($completedJobOrderIconIndicator, 'before')
                ->descriptionColor($completedJobOrderIndicator)
                ->iconColor($completedJobOrderIndicator),
        ];
    }

    private function countOrderByStatus($status) {
        return JobOrder::join('job_order_status_types as jost', 'jost.id', 'job_order_status_type_id')
                    ->where('jost.name', $status)
                    ->count();
    }

    private function getCustomerCountPerMonth($month) {
        return Customer::whereMonth('created_at', Carbon::parse(Carbon::now()->month($month)->format('Y-m')))->count();
    }

    private function getJobOrderCountPerMonth($month) {
        return JobOrder::whereMonth('created_at', Carbon::parse(Carbon::now()->month($month)->format('Y-m')))->count();
    }

    private function countOrderByStatusAndMonth($month, $status) {
        return JobOrder::from('job_orders as jo')->join('job_order_status_types as jost', 'jost.id', 'jo.job_order_status_type_id')
                    ->where('jost.name', $status)
                    ->whereMonth('jo.created_at', Carbon::parse(Carbon::now()->month($month)->format('Y-m')))
                    ->count();
    }
}
