<?php

namespace App\Console\Commands;

use App\Models\Contract;
use App\Models\User;
use App\Notifications\ContractDueTomorrowNotification;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

#[Signature('app:check-due-deadlines')]
#[Description('Command description')]
class CheckDueDeadlines extends Command
{
    public function handle()
    {
        $this->info('Checking contract deadlines...');

        $dueContracts = Contract::where(function ($query) {
            $query->where('turnaround_date', '<=', now()->toDateString())
                ->orWhere('deadline', '<=', now()->toDateString());
        })
            ->whereNotIn('status', ['due', 'executed'])
            ->get();

        // Get the current max position for 'due' status to prevent unique constraint violations
        $maxPosition = Contract::where('status', 'due')->max('position') ?? 0;

        foreach ($dueContracts as $contract) {
            $maxPosition += 1000; // Increment by a safe margin

            $contract->update([
                'status' => 'due',
                'position' => $maxPosition,
            ]);
        }

        // Check for contracts due tomorrow
        $this->info('Checking for contracts due tomorrow...');
        
        $tomorrowString = now()->addDay()->toDateString();
        $dueTomorrowContracts = Contract::where(function ($query) use ($tomorrowString) {
            $query->where('turnaround_date', '=', $tomorrowString)
                ->orWhere('deadline', '=', $tomorrowString);
        })
            ->whereNotIn('status', ['due', 'executed'])
            ->with(['assignees', 'creator']) // Eager load assignees and creator
            ->get();

        foreach ($dueTomorrowContracts as $contract) {
            // Notify assignees
            foreach ($contract->assignees as $assignee) {
                try {
                    $user = User::where('empNo', $assignee->EmpNo)->first();
                    if ($user) {
                        $user->notify(new ContractDueTomorrowNotification($contract));
                    } elseif ($assignee->EmpEmailAd) {
                        Notification::route('mail', $assignee->EmpEmailAd)
                            ->notify(new ContractDueTomorrowNotification($contract));
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to notify assignee about contract due tomorrow: ' . $e->getMessage());
                }
            }
            
            // Notify creator
            if ($contract->creator) {
                try {
                    $contract->creator->notify(new ContractDueTomorrowNotification($contract));
                } catch (\Exception $e) {
                    Log::error('Failed to notify creator about contract due tomorrow: ' . $e->getMessage());
                }
            }
        }

        $this->info('Done!');
    }
}
