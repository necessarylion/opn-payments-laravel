<?php
namespace OpnPayments\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use OpnPayments\Models\OpnPaymentsAttempt;

class OpnPaymentCompleted {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ?OpnPaymentsAttempt $attempt = null;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(OpnPaymentsAttempt $attempt) {
        $this->attempt = $attempt;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return new PrivateChannel('opn.payments.' . $this->attempt->order_id);
    }
}
