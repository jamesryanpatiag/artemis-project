<?php

namespace App\Filament\Resources\JobOrderResource\Pages;

use App\Filament\Resources\JobOrderResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use App\Models\JobOrderStatusType;
use App\Models\JobOrder;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Redirect; // Or use the global redirect() helper
use Log;

class EditJobOrder extends EditRecord
{
    protected static string $resource = JobOrderResource::class;

    protected $listeners = ['refreshHeaderActions' => '$refresh'];

    protected function getHeaderActions(): array
    {
        if (auth()->user()->approver && 
            $this->record->assignedDepartment->id == auth()->user()->department_id && 
            $this->record->jobOrderStatusType->needApproval()) {
            return [
                Actions\DeleteAction::make(),
                Action::make('approve')
                    ->requiresConfirmation()
                    ->label('Approve')
                    ->modalHeading('Approve?')
                    ->action(function (JobOrder $record) {
                        $approvedStatus = JobOrderStatusType::where('name', 'Approved')->first();
                        $this->record->job_order_status_type_id = $approvedStatus->id;
                        $this->record->save();
                        $this->record->refresh();
                        Notification::make()
                            ->title('Saved successfully')
                            ->success()
                            ->send();
                        Redirect::to(JobOrderResource::getUrl('edit', ['record' => $record]));
                    }),
            ];       
        } else {
            return [
                Actions\DeleteAction::make(),
            ];
        }
    }

    public function afterSave() {
        $this->refreshFormData([
            'job_order_status_type_id'
        ]);
        $this->dispatch('refreshHeaderActions');
    }
}
