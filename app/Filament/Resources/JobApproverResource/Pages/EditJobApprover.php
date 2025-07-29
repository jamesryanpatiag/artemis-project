<?php

namespace App\Filament\Resources\JobApproverResource\Pages;

use App\Filament\Resources\JobApproverResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobApprover extends EditRecord
{
    protected static string $resource = JobApproverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
