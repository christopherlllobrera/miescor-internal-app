<?php

namespace App\Observers;

use App\Models\Contract;
use App\Notifications\ContractStatusUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

// Timely checks the status change
class ContractObserver
{
    /**
     * Handle the Contract "created" event.
     */
    public function created(Contract $contract): void
    {
        //
    }

    public function updated(Contract $contract): void
    {
        $statusChanged = $contract->wasChanged('status');
        $assigneeChanged = $contract->wasChanged('assigned_to');

        if ($statusChanged) {
            if ($contract->creator) {
                try {
                    $contract->creator->notify(new ContractStatusUpdated($contract));
                } catch (\Exception $e) {
                    Log::error('Failed to notify creator: '.$e->getMessage());
                    \Filament\Notifications\Notification::make()
                        ->title('Email Failed')
                        ->body('Could not send status update email to the creator.')
                        ->danger()
                        ->send();
                }
            }
        }

        if (($statusChanged || $assigneeChanged) && $contract->assignee && $contract->assignee->EmpEmailAd) {
            try {
                if ($statusChanged && $contract->status === 'due') {
                    Notification::route('mail', $contract->assignee->EmpEmailAd)
                        ->notify(new \App\Notifications\ContractDueNotification($contract));
                } else {
                    Notification::route('mail', $contract->assignee->EmpEmailAd)
                        ->notify(new ContractStatusUpdated($contract));
                }
            } catch (\Exception $e) {
                Log::error('Failed to notify assignee: '.$e->getMessage());
                \Filament\Notifications\Notification::make()
                    ->title('Email Failed')
                    ->body('Could not send status update email to the assignee.')
                    ->danger()
                    ->send();
            }
        }
    }

    /**
     * Handle the Contract "deleted" event.
     */
    public function deleted(Contract $contract): void
    {
        //
    }

    /**
     * Handle the Contract "restored" event.
     */
    public function restored(Contract $contract): void
    {
        //
    }

    /**
     * Handle the Contract "force deleted" event.
     */
    public function forceDeleted(Contract $contract): void
    {
        //
    }
}
