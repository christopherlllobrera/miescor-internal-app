<?php

namespace App\Notifications;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractAssignedForReview extends Notification
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $sharepointLink = $this->contract->attachment ?: url('/'); // Fallback if no link

        return (new MailMessage)
            ->subject('Action Required: Contract '.$this->contract->contract_title.' is ready for your review.')
            ->view('emails.contract-assigned-for-review', [
                'contract' => $this->contract,
                'notifiable' => $notifiable,
                'sharepointLink' => $sharepointLink,
            ]);
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
