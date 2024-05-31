<?php

namespace App\Listeners;

use App\Events\PaymentSuccess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class SendWhatsappMessage implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentSuccess $event)
    {
        $order = $event->order;
        $message = "Thank you for your purchase! Your order number is {$order->id}.";

        try {
            $this->twilio->messages->create(
                'whatsapp:' . $order->phone,
                [
                    'from' => config('services.twilio.whatsapp_from'),
                    'body' => $message
                ]
            );

            Log::info('WhatsApp message sent successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp message: ' . $e->getMessage());
        }
    }
}
