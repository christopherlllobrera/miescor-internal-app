<?php

namespace App\Notifications;

use App\Models\Contract;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractStatusUpdated extends Notification
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

    // Contents of the Mail and Email sending
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Contract Status Updated: '.$this->contract->reference_no)
            ->view('emails.contract-status-updated', [
                'contract' => $this->contract,
                'notifiable' => $notifiable,
            ]);
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('Contract Status Updated')
            ->body('The status for the contract "'.$this->contract->reference_no.' is now '.$this->contract->status)
            ->success()
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
