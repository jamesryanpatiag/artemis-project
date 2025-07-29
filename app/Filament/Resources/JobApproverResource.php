<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobApproverResource\Pages;
use App\Filament\Resources\JobApproverResource\RelationManagers;
use App\Models\JobApprover;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\UserRole;
use App\Models\Department;

class JobApproverResource extends Resource
{
    protected static ?string $model = JobApprover::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Job Approver')
                    ->icon('heroicon-o-arrow-up-circle')
                    ->schema([
                        Forms\Components\Select::make('user_role_id')
                            ->label('Role')
                            ->options(UserRole::all()->pluck('name', 'id'))
                            ->searchable(),
                        Forms\Components\Select::make('department_id')
                            ->label('Department')
                            ->options(Department::all()->pluck('name', 'id'))
                            ->searchable()
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('approver_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('job_order_status_type_id')
                    ->numeric()
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
            'index' => Pages\ListJobApprovers::route('/'),
            'create' => Pages\CreateJobApprover::route('/create'),
            'edit' => Pages\EditJobApprover::route('/{record}/edit'),
        ];
    }
}
