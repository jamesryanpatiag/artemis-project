<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobOrderResource\Pages;
use App\Filament\Resources\JobOrderResource\RelationManagers;
use App\Models\JobOrder;
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

class JobOrderResource extends Resource
{
    protected static ?string $model = JobOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

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
                            ->options(JobOrderStatusType::all()->pluck('name', 'id'))
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jobOrderStatusType.name')
                    ->label('Status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('job_order_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expected_start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expected_end_date')
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
            //
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
