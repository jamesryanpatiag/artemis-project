<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Models\JobOrder;
use App\Models\JobOrderStatusType;
use Filament\Support\Facades\FilamentColor;

class HighPriorityJobOrder extends BaseWidget
{
    protected static ?string $heading = 'Urgent/High Job Orders';

    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                JobOrder::query()
                        ->join('priority_statuses as ps', 'ps.id', 'priority_status_id')
                        ->join('job_order_status_types as status', 'status.id', 'job_order_status_type_id')
                        ->whereNotIn('status.name', ['Void', 'Cancelled', 'Closed', 'Completed'])
                    ->whereIn('ps.name', ['High', 'Urgent'])
            )
            ->columns([
                TextColumn::make('job_order_number'),
                TextColumn::make('expected_end_date')->label('Commit Date'),
                BadgeColumn::make('jobOrderStatusType.name')
                    ->label('Status')
                    ->color(static function ($state): string {
                        $data = JobOrderStatusType::where('name', $state)->first();
                        $formattedData = str_replace([' ', '/', '-'], '_', $data->name);
                        if ($data->color) {
                            FilamentColor::register([
                                $formattedData => $data->color
                            ]);
                            return $formattedData;
                        } else {
                            return 'info';
                        }
                    })
                    ->sortable(),
                TextColumn::make('assignedDepartment.name')->label('Assigned Department'),
            ]);
    }
}
