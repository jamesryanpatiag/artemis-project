<?php

namespace App\Filament\Resources\JobOrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\RichEditor;

class JobOrderDocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'jobOrderDocuments';

    protected static ?string $title = 'Documents';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                    RichEditor::make('description')
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('uploaded_file')
                        ->disk('local')
                        ->directory('uploads')
                        ->visibility('public')
                        ->downloadable(),
                ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('description')
                ->html(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Upload Document'),
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
