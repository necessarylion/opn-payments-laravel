<?php
namespace App\Listeners;

use OpnPayments\Events\OpnPaymentCompleted;

class OpnPaymentHandler {
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\PaymentCompleted $event
     * @return void
     */
    public function handle(OpnPaymentCompleted $event) {
        $attempt = $event->attempt;
        $charge = $event->charge;
        if ($attempt->payment_successful) {
            // handle payment success here
        } else {
            // handle payment failed here
        }
    }
}
