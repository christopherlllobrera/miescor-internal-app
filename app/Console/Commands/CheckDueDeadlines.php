<?php

namespace App\Console\Commands;

use App\Models\Contract;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


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
            ->whereNotIn('status', ['due', 'completed'])
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

        $this->info('Done!');
    }
}
