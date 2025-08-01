<?php

namespace App\Filament\Resources\JobOrderStatusTypeStepResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\JobOrderStatusType;
use Log;

class JobOrderStatusTypeStepsRelationManager extends RelationManager
{
    protected static string $relationship = 'jobOrderStatusTypeSteps';

    protected static ?string $title = 'Next Step';

    public function form(Form $form): Form
    {
        // Log::info($this->getOwnerRecord());
        return $form
            ->schema([
                Forms\Components\Select::make('child_job_status_type_id')
                            ->label('Status')
                            ->options(JobOrderStatusType::whereNotIn('id', [$this->getOwnerRecord()->id])->get()->pluck('name', 'id'))
                            ->searchable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('child_job_status_type_id')
            ->columns([
                Tables\Columns\TextColumn::make('childJobStatusType.name')->label('Status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New Next Step'),
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
}
