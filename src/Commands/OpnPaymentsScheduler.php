<?php

namespace OpnPayments\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use OpnPayments\Events\OpnPaymentCompleted;
use OpnPayments\Models\OpnPaymentsAttempt;
use OpnPayments\Models\OpnPaymentsCharge;
use OpnPayments\OpnPayments;

class OpnPaymentsScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opn-payments-scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Opn Payments pending charge handler';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $paymentAttempts = OpnPaymentsAttempt::where('payment_successful', 0)
            ->whereHas('charges', function($query) {
                $query->where('status', 'pending')
                ->where('created_at', '>', now()->subHours(24));
            })
            ->limit(10)
            ->get();

        foreach($paymentAttempts as $paymentAttempt) {
            $charges = $paymentAttempt->pendingCharges;
            foreach ($charges as $charge) {
                $this->completeCharge($charge, $paymentAttempt);
            }
        }
    }

    public function completeCharge($charge, $paymentAttempt) {
        $this->info('Checking Charge ID : ' . $charge->charge_id);
        $chargeResult = OpnPayments::charge()->retrieve($charge->charge_id);
        $paymentSuccessful = OpnPayments::paymentSuccessful($chargeResult, $paymentAttempt);
        $this->info('Payment Successful : ' . ($paymentSuccessful ? 'Yes' : 'No'));
        $charge->payment_successful = $paymentSuccessful;
        $charge->status             = $charge->status;
        $charge->failure_code       = $charge->failure_code;
        $charge->save();
        $paymentAttempt->payment_successful = $paymentSuccessful;
        $paymentAttempt->save();
        if ($charge->status != OpnPaymentsCharge::STATUS_PENDING) {
            OpnPaymentCompleted::dispatch($paymentAttempt, $charge);
        }
    }
}
