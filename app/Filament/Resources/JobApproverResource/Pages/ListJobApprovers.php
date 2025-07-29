<?php

namespace App\Filament\Resources\JobApproverResource\Pages;

use App\Filament\Resources\JobApproverResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobApprovers extends ListRecords
{
    protected static string $resource = JobApproverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
