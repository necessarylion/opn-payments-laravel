<?php
namespace OpnPayments\Traits;

use Illuminate\Support\Facades\View;

trait OpnPaymentsPayJsHelper {
    public static function getTheme() {
        return config('opn-payments.theme');
    }

    public static function getPayJsContent($amount, $currency) {
        $opnPayment = self::init();
        $file       = __DIR__ . '/../../views/pay-js.blade.php';
        return View::file($file, [
            'amount'         => $amount,
            'currency'       => $currency,
            'publicKey'      => $opnPayment->key()->public,
            'theme'          => $opnPayment->getTheme()['color'],
            'paymentMethods' => json_encode($opnPayment->paymentMethods()),
        ]);
    }
}