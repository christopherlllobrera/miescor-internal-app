<?php

namespace App\Notifications;

use App\Models\Contract;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractDueTomorrowNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Contract $contract)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $notifiable instanceof AnonymousNotifiable
            ? ['mail']
            : ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reminder: Contract Due Tomorrow - '.$this->contract->reference_no)
            ->view('emails.contract-due-tomorrow', [
                'contract' => $this->contract,
                'notifiable' => $notifiable,
            ]);
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Contract Due Tomorrow')
            ->body('The deadline or turnaround time for the contract "'.$this->contract->contract_title.'" is due tomorrow.')
            ->warning()
            ->getDatabaseMessage();
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
