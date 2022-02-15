<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderCancel extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$order_id)
    {
        $this->user = $user;
        $this->order_id = $order_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }


    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = $this->user;
        $order_id = $this->order_id;

        $url = url('/my-order-details/'.$order_id);
        return (new MailMessage)
        ->greeting("Thanks For Using JSD Agro")
        ->line("Hi, ".$user->name)
        ->line("We Got A Cancel Request From You.")
        ->line("We Are Cancelling Your Order.")
        ->action('Click To See Your Order Details', url($url))
        ->line("In case Of any query you can contact us on info@jsdagro.com")
        ->line("Thanks")
        ->line("Visit us Again");

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
