<?php
namespace OpnPayments\Controllers;

use Exception;
use Illuminate\Support\Facades\View;
use OpnPayments\Models\OpnPaymentsAttempt;
use OpnPayments\OpnPayments;

class PaymentController extends Controller {
    public function payJsForm($orderId) {
        $attempt = OpnPaymentsAttempt::where('order_id', $orderId)->first(); 
        if (!$attempt) {
            throw new Exception($orderId . ' Not found');
        }
        $file = __DIR__ . '/../../views/pay-js.blade.php';
        return View::file($file, [
            'amount'         => OpnPayments::castCurrency($attempt->amount, $attempt->currency),
            'currency'       => $attempt->currency,
            'publicKey'      => OpnPayments::key()->public,
            'theme'          => OpnPayments::getTheme()['color'],
            'orderId'        => $orderId,
            'locale'         => $attempt->language,
            'paymentMethods' => json_encode($attempt->payment_methods),
        ]);
    }

    public function renderPayment($orderId) {
        $attempt = OpnPaymentsAttempt::where('order_id', $orderId)->first(); 
        if (!$attempt) {
            throw new Exception($orderId . ' Not found');
        }
        return view('payment', [
            'amount'   => $attempt->amount, 
            'currency' => $attempt->currency,
            'backUrl'  => $attempt->cancel_uri,
            'orderId'  => $orderId,
            'locale'   => $attempt->language,
            'theme'    => OpnPayments::getTheme(),
        ]);
    }
}
