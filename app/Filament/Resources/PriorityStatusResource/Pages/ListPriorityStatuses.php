<?php

namespace App\Filament\Resources\PriorityStatusResource\Pages;

use App\Filament\Resources\PriorityStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPriorityStatuses extends ListRecords
{
    protected static string $resource = PriorityStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
