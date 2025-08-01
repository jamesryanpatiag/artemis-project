<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobOrderResource\Pages;
use App\Filament\Resources\JobOrderResource\RelationManagers\JobOrderServiceLaborsRelationManager;
use App\Filament\Resources\JobOrderResource\RelationManagers\JobOrderPartsMaterialsRelationManager;
use App\Filament\Resources\JobOrderResource\RelationManagers\JobOrderDocumentsRelationManager;
use App\Models\JobOrder;
use App\Models\JobOrderStatusTypeStep;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use App\Models\Customer;
use App\Models\Department;
use App\Models\JobOrderStatusType;
use Filament\Forms\Components\RichEditor;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Enums\IconPosition;
use Filament\Forms\Get;
use DB;
use Log;

class JobOrderResource extends Resource
{
    protected static ?string $model = JobOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = 'Orders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Details')
                    ->icon('heroicon-o-document')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->label('Customer')
                            ->rules(['required'])
                            ->relationship('customer', 'name')
                            ->options(Customer::all()->pluck('name', 'id'))
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                ->required(),
                                Forms\Components\TextInput::make('email')
                                    ->email(),
                                Forms\Components\TextInput::make('contact_number'),
                                Forms\Components\Textarea::make('address')
                                    ->columnSpanFull(),
                            ]),
                        Forms\Components\DatePicker::make('job_order_date')
                            ->default(now())
                            ->rules(['required']),

                        Forms\Components\DatePicker::make('expected_start_date')
                            ->rules(['required']),
                        Forms\Components\DatePicker::make('expected_end_date')
                            ->label('Commit Date')
                            ->rules(['required']),
                        Forms\Components\Select::make('assigned_department_id')
                            ->label('Assigned Department')
                            ->options(Department::all()->pluck('name', 'id'))
                            ->searchable()
                            ->rules(['required']),
                        RichEditor::make('work_description')
                            ->rules(['required'])
                            ->columnSpanFull(),
                    ])->columns(2)->columnSpan(3),
                Section::make('Info')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Forms\Components\TextInput::make('job_order_number')->rules(['required']),
                        Forms\Components\Select::make('job_order_status_type_id')
                            ->label('Job Order Status Type')
                            // ->options(JobOrderStatusType::all()->pluck('name', 'id'))
                            ->options(function () use ($form) {
                                if ($form->getRecord() != null) {
                                    $childData = JobOrderStatusTypeStep::join('job_order_status_types as parent', 'parent.id', 'parent_id_job_status_type_id')
                                        ->join('job_order_status_types as child', 'child.id', 'child_job_status_type_id')
                                        ->where('parent_id_job_status_type_id', $form->getRecord()->job_order_status_type_id)
                                        ->select('child.name', 'child.id');
                                    $parentData = JobOrderStatusType::where('id', $form->getRecord()->job_order_status_type_id)
                                        ->union($childData)
                                        ->pluck('name', 'id');
                                    return $parentData;   
                                }
                                return JobOrderStatusType::where('id', 1)->pluck('name', 'id');
                            })
                            ->searchable()
                            ->rules(['required'])
                    ])->columnSpan(1)
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('job_order_number')
                    ->icon('heroicon-m-hashtag')
                    ->iconPosition(IconPosition::Before)
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->icon('heroicon-m-user')
                    ->iconPosition(IconPosition::Before)
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('jobOrderStatusType.name')
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
                Tables\Columns\TextColumn::make('assignedDepartment.name')
                    ->icon('heroicon-m-building-office-2')
                    ->iconPosition(IconPosition::Before)
                    ->label('Department')
                    ->sortable(),
                Tables\Columns\TextColumn::make('job_order_date')
                    ->icon('heroicon-m-calendar')
                    ->iconPosition(IconPosition::Before)
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expected_start_date')
                    ->icon('heroicon-m-calendar')
                    ->iconPosition(IconPosition::Before)
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expected_end_date')
                    ->icon('heroicon-m-calendar')
                    ->label('Commit Date')
                    ->iconPosition(IconPosition::Before)
                    ->date()
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            JobOrderDocumentsRelationManager::class,
            JobOrderServiceLaborsRelationManager::class,
            JobOrderPartsMaterialsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobOrders::route('/'),
            'create' => Pages\CreateJobOrder::route('/create'),
            'edit' => Pages\EditJobOrder::route('/{record}/edit'),
        ];
    }
}
