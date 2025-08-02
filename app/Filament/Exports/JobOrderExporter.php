<?php

namespace App\Filament\Exports;

use App\Models\JobOrder;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class JobOrderExporter extends Exporter
{
    protected static ?string $model = JobOrder::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('customer.name'),
            ExportColumn::make('assignedDepartment.name'),
            ExportColumn::make('job_order_date'),
            ExportColumn::make('job_order_number'),
            ExportColumn::make('expected_start_date'),
            ExportColumn::make('expected_end_date'),
            ExportColumn::make('work_description'),
            ExportColumn::make('jobOrderStatusType.name'),
            ExportColumn::make('priorityStatus.name'),
            ExportColumn::make('po_number'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your job order export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
