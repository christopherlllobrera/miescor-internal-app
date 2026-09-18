<?php

namespace App\Observers;

use App\Models\Contract;
use App\Notifications\ContractDueNotification;
use App\Notifications\ContractStatusUpdated;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as IlluminateNotification;

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

    public function updating(Contract $contract): void
    {
        if (in_array($contract->getOriginal('status'), ['pending', 'proponent-pending']) && $contract->isDirty('assigned_to') && ! empty($contract->assigned_to)) {
            $contract->status = 'in-progress';
        }
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
                    Notification::make()
                        ->title('Email Failed')
                        ->body('Could not send status update email to the creator.')
                        ->danger()
                        ->send();
                }
            }
        }

        if ($statusChanged) {
            foreach ($contract->assignees as $assignee) {
                if ($assignee->EmpEmailAd) {
                    try {
                        if ($contract->status === 'due') {
                            IlluminateNotification::route('mail', $assignee->EmpEmailAd)
                                ->notify(new ContractDueNotification($contract));
                        } else {
                            IlluminateNotification::route('mail', $assignee->EmpEmailAd)
                                ->notify(new ContractStatusUpdated($contract));
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to notify assignee: '.$e->getMessage());
                        Notification::make()
                            ->title('Email Failed')
                            ->body('Could not send status update email to the assignee.')
                            ->danger()
                            ->send();
                    }
                }
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
