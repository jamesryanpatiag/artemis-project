<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobOrderStatusTypeResource\Pages;
use App\Filament\Resources\JobOrderStatusTypeResource\RelationManagers;
use App\Filament\Resources\JobOrderStatusTypeStepResource\RelationManagers\JobOrderStatusTypeStepsRelationManager;
use App\Models\JobOrderStatusType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\ColorColumn;

class JobOrderStatusTypeResource extends Resource
{
    protected static ?string $model = JobOrderStatusType::class;
    
    protected static ?string $navigationGroup = 'Content Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Status')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->rules(['required'])
                        ->columnSpan(4),
                    Forms\Components\ColorPicker::make('color')
                        ->rules(['required'])
                        ->columnSpan(1),
                    Forms\Components\Toggle::make('need_approver'),
                ])->columns(5)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('job_order_status_type_steps_count')
                    ->label('Steps')
                    ->counts('jobOrderStatusTypeSteps')
                    ->badge()
                    ->searchable(),
                Tables\Columns\BooleanColumn::make('need_approver')
                    ->searchable(),
                Tables\Columns\ColorColumn::make('color')
                    ->searchable(),
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
                Tables\Actions\DeleteAction::make(),
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
            JobOrderStatusTypeStepsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobOrderStatusTypes::route('/'),
            'create' => Pages\CreateJobOrderStatusType::route('/create'),
            'edit' => Pages\EditJobOrderStatusType::route('/{record}/edit'),
        ];
    }
}
