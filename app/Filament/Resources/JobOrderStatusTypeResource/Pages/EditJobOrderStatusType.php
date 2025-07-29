<?php

namespace App\Filament\Resources\JobOrderStatusTypeResource\Pages;

use App\Filament\Resources\JobOrderStatusTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobOrderStatusType extends EditRecord
{
    protected static string $resource = JobOrderStatusTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
