<?php



namespace App\Notifications;

use Illuminate\Support\Facades\Crypt;

use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Notification;

use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Notifications\Messages\MailMessage;



class OrderinvoiceStore extends Notification

{

    use Queueable;



    /**

     * Create a new notification instance.

     *

     * @return void

     */

    public function __construct($odData,$data)

    {

        $this->data = $data;

        $this->odData = $odData;

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

        $data = $this->data;

        $encrypted_order_id = Crypt::encryptString($data['order_id']);

        $odData = $this->odData;

        $url = url('/my-order-details/'.$encrypted_order_id);

        return (new MailMessage)

        ->greeting("One new order have been placed")

        ->subject('New Order from JSD Agro')

        ->line("Hi, ")

        ->line("One order have been placed.")

        ->line("Customer Name: ".$data['billing_name'])

        ->line("Delivery Address:  ".$data['shipping_address'])

        ->line("Customer Phone No: ".$data['shipping_phone'])

        ->line("Order No: ".$data['order_number'])

        //->line("Order Value: ".$data['total_amount'])
        
        ->line("Order Value: र ".number_format($data['total_amount'],2))

        ->action('Click For Details', url($url))

        ->line("Thanks");



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

