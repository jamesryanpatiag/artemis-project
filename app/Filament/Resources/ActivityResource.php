<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Filament\Resources\ActivityResource\RelationManagers;
use App\Models\Activity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Cache;
use App\Models\JobOrder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Department;
use App\Models\JobOrderStatusType;
use App\Models\PriorityStatus;
use Log;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Audit Logs';

    protected static ?int $navigationSort = 999;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('log_name')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('subject_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('event')
                    ->maxLength(255),
                Forms\Components\TextInput::make('subject_id')
                    ->numeric(),
                Forms\Components\TextInput::make('causer_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('causer_id')
                    ->numeric(),
                Forms\Components\TextInput::make('properties'),
                Forms\Components\TextInput::make('batch_uuid')
                    ->maxLength(36),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject_id')
                    ->label('Job Order Number')
                    ->state(function ($record): string {
                        $jobOrder = Cache::remember('job_order_activity_' . $record['subject_id'], 600, function () use ($record) {
                            return JobOrder::find($record['subject_id']);
                        });
                        return $jobOrder->job_order_number;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('from')
                    ->label('From:')
                    ->state(function ($record): string {
                        $properties = $record['properties'];
                        if (isset($properties['old'])) {
                            $html = '<ul>';
                            foreach ($properties['old'] as $key => $value) {
                                $html .= '<li style="font-size:12px">' . self::translateColumn($key) . ' : ' . self::translateColumnValue($key, $value) . '</li>';
                            }
                            $html .= "</ul>";
                            return $html;
                        }
                        return "";
                    })
                    ->html()
                    ->wrap(),
                Tables\Columns\TextColumn::make('to')
                    ->label('To:')
                    ->state(function ($record): string {
                        $properties = $record['properties'];
                        $html = '<ul>';
                        foreach ($properties['attributes'] as $key => $value) {
                            $html .= '<li style="font-size:12px">' . self::translateColumn($key) . ' : ' . self::translateColumnValue($key, $value) . '</li>';
                        }
                        $html .= "</ul>";
                        return $html;
                    })
                    ->html()
                    ->wrap(),
                Tables\Columns\TextColumn::make('causer_id')
                    ->label('Modified By')
                    ->state(function ($record): string {
                        $user = Cache::remember('user_activity_' . $record['causer_id'], 600, function () use ($record) {
                            return User::find($record['causer_id']);
                        });
                        return $user->name;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])            
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            // 'create' => Pages\CreateActivity::route('/create'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }

    public static function translateColumn($column) {
        
        switch ($column) {
            case 'id':
                return 'ID';
            break;
            case 'customer_id':
                return 'Customer';
            break;
            case 'assigned_department_id':
                return 'Assigned Department';
            break;
            case 'job_order_date':
                return 'Job order date';
            break;
            case 'job_order_number':
                return 'Job order number';
            break;
            case 'expected_start_date':
                return 'Expected start date';
            break;
            case 'expected_end_date':
                return 'Commit Date';
            break;
            case 'work_description':
                return 'Work Description';
            break;
            case 'job_order_status_type_id':
                return 'Job Order Status Type';
            break;
            case 'priority_status_id':
                return 'Priority Status';
            break;
            case 'po_number':
                return 'PO Number';
            break;
        }
        return $column;
    }

    public static function translateColumnValue($column, $value) {
        
        switch ($column) {
            case 'customer_id':
                $user = Cache::remember('user_' . $value, 600, function () use ($value) {
                    return Customer::find($value);
                });
                return $user->name ?? '';
            break;
            case 'assigned_department_id':
                $department = Cache::remember('department_' . $value, 600, function () use ($value) {
                    return Department::find($value);
                });
                return $department->name ?? '';
            break;
            case 'job_order_status_type_id':
                $jobOrderStatusType = Cache::remember('job_order_status_type_' . $value, 600, function () use ($value) {
                    return JobOrderStatusType::find($value);
                });
                return $jobOrderStatusType->name ?? '';
            break;
            case 'priority_status_id':
                $priorityStatus = Cache::remember('priority_status_' . $value, 600, function () use ($value) {
                    return PriorityStatus::find($value);
                });
                return $priorityStatus->name ?? '';
            break;
        }
        return $value;
    }
}
