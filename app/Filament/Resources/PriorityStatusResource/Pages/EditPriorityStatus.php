<?php

namespace App\Filament\Resources\PriorityStatusResource\Pages;

use App\Filament\Resources\PriorityStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPriorityStatus extends EditRecord
{
    protected static string $resource = PriorityStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
