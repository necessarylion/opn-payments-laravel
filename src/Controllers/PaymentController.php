<?php
namespace OpnPayments\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use OpnPayments\Events\OpnPaymentCompleted;
use OpnPayments\Models\OpnPaymentsAttempt;
use OpnPayments\Models\OpnPaymentsCharge;
use OpnPayments\OpnPayments;
use OpnPayments\Types\OpnPaymentsChargePayload;

class PaymentController extends Controller {
    public function payJsForm($orderId) {
        $attempt = OpnPaymentsAttempt::where('order_id', $orderId)->first(); 
        if (!$attempt) {
            throw new Exception($orderId . ' Not found');
        }
        $file  = __DIR__ . '/../../views/pay-js.blade.php';
        $style = config('opn-payments.omise-js-style');
        return View::file($file, [
            'amount'         => $attempt->opn_amount,
            'currency'       => $attempt->currency,
            'publicKey'      => OpnPayments::key()->public,
            'theme'          => OpnPayments::getTheme()['color'],
            'orderId'        => $orderId,
            'locale'         => $attempt->language,
            'style'          => json_encode($style),
            'paymentMethods' => json_encode($attempt->payment_methods),
        ]);
    }

    public function renderPayment($orderId) {
        $prefix = config('opn-payments.route_prefix', 'opn-payments');
        $attempt = OpnPaymentsAttempt::where('order_id', $orderId)->first(); 
        if (!$attempt) {
            throw new Exception($orderId . ' Not found');
        }
        if ($attempt->payment_successful) {
            return redirect("/$prefix/success/" . $attempt->order_id);
        }
        $config = config('opn-payments');
        return view('opn-payment', [
            'amount'   => $attempt->amount, 
            'currency' => $attempt->currency,
            'backUrl'  => $attempt->cancel_uri,
            'orderId'  => $orderId,
            'prefix'   => $prefix,
            'locale'   => $attempt->language,
            'config'   => $config,
        ]);
    }

    public function charge(Request $request, $orderId) {
        $request->validate([
            'token' => 'required',
        ]);
        $prefix = config('opn-payments.route_prefix', 'opn-payments');
        $attempt = OpnPaymentsAttempt::where('order_id', $orderId)->first(); 
        if (!$attempt) {
            throw new Exception($orderId . ' Not found');
        }
        $charge            = new OpnPaymentsChargePayload();
        $charge->amount    = $attempt->opn_amount;
        $charge->token     = $request->token;
        $charge->currency  = $attempt->currency;
        $charge->language  = $attempt->language;
        $charge->ip        = $request->ip();
        $charge->metaData  = $attempt->meta_data;
        $charge->returnUri = config('app.url') . "/$prefix/complete/" . $attempt->order_id;
        $result            = OpnPayments::createCharge($charge);

        $qrCode            = OpnPayments::getQR($result->source);
        $paymentMethod     = OpnPayments::getPaymentMethod($result->source);
        $paymentSuccessful = OpnPayments::paymentSuccessful($result, $attempt);

        $attempt->payment_successful = $paymentSuccessful;
        $attempt->save();

        $paymentCharge                          = new OpnPaymentsCharge();
        $paymentCharge->opn_payments_attempt_id = $attempt->id;
        $paymentCharge->charge_id               = $result->id;
        $paymentCharge->payment_successful      = $paymentSuccessful;
        $paymentCharge->status                  = $result->status;
        $paymentCharge->payment_method          = $paymentMethod;
        $paymentCharge->failure_code            = $result->failure_code;
        $paymentCharge->save();

        return [
            'qrcode'       => $qrCode,
            'redirect_uri' => $result->authorize_uri,
        ];
    }

    public function complete($orderId) {
        $prefix = config('opn-payments.route_prefix', 'opn-payments');
        $attempt       = OpnPaymentsAttempt::where('order_id', $orderId)->first(); 
        $paymentCharge = $attempt->charge();
        if (!$attempt) {
            throw new Exception($orderId . ' Not found');
        }
        if (!$paymentCharge) {
            throw new Exception('Charge Not found');
        }
        $charge = $this->completePayment($attempt);
        $paymentSuccessful = OpnPayments::paymentSuccessful($charge, $attempt);
        if ($paymentSuccessful) {
            return redirect("/$prefix/success/" . $attempt->order_id);
        }
        return redirect("/$prefix/failed/" . $attempt->order_id);
    }

    public function status($orderId) {
        $attempt       = OpnPaymentsAttempt::where('order_id', $orderId)->first(); 
        $paymentCharge = $attempt->charge();
        if (!$attempt) {
            return ['status' => false];
        }
        if (!$paymentCharge) {
            return ['status' => false];
        }
        $charge = $this->completePayment($attempt);
        if ($charge->status == OpnPaymentsCharge::STATUS_PENDING) {
            return [ 'status' => false];
        }
        return ['status' => true];
    }

    public function success($orderId) {
        $prefix = config('opn-payments.route_prefix', 'opn-payments');
        $attempt = OpnPaymentsAttempt::where('order_id', $orderId)->first(); 
        $charge = $this->completePayment($attempt);
        if (!$attempt) {
            return ['status' => false];
        }
        if (!$attempt->payment_successful) {
            return redirect("/$prefix/failed/" . $attempt->order_id);
        }
        return view('opn-success', [
            'config'  => config('opn-payments'),
            'attempt' => $attempt,
            'charge' => $charge,
        ]);
    }

    public function failed($orderId) {
        $prefix = config('opn-payments.route_prefix', 'opn-payments');
        $attempt = OpnPaymentsAttempt::where('order_id', $orderId)->first(); 
        $charge  = $this->completePayment($attempt);
        if (!$attempt) {
            return ['status' => false];
        }
        if ($attempt->payment_successful) {
            return redirect("/$prefix/success/" . $attempt->order_id);
        }
        return view('opn-failed', [
            'config'  => config('opn-payments'),
            'attempt' => $attempt,
            'charge' => $charge,
        ]);
    }

    private function completePayment($attempt) {
        $paymentCharge     = $attempt->charge();
        if(!$paymentCharge) {
            return null;
        }
        $charge            = OpnPayments::charge()->retrieve($paymentCharge->charge_id);
        $paymentSuccessful = OpnPayments::paymentSuccessful($charge, $attempt);

        $paymentCharge->payment_successful = $paymentSuccessful;
        $paymentCharge->status             = $charge->status;
        $paymentCharge->failure_code       = $charge->failure_code;
        $paymentCharge->save();
        $attempt->payment_successful = $paymentSuccessful;
        $attempt->save();

        if ($charge->status != OpnPaymentsCharge::STATUS_PENDING) {
            OpnPaymentCompleted::dispatch($attempt);
        }
        return $charge;
    }
}
