<?php
namespace OpnPayments\Traits;

use OpnPayments\Models\OpnPaymentsCharge;

trait OpnPaymentsPayJsHelper {
    public static function getTheme() {
        return config('opn-payments.theme');
    }

    public static function getPaymentMethod($source) {
        $paymentMethod = 'credit_card';
        if ($source) {
            $paymentMethod = $source->type;
        }
        return $paymentMethod;
    }

    public static function getQR($source) {
        $qrCode = null;
        if ($source) {
            $scan = $source->scannable_code;
            if ($scan && $scan->image) {
                $qrCode = $scan->image->download_uri;
            }
        }
        return $qrCode;
    }

    public static function paymentSuccessful($charge, $attempt) {
        return (
            $attempt->manual_capture 
            && $charge->authorized
        )
        || (
            !$attempt->manual_capture 
            && $charge->status == OpnPaymentsCharge::STATUS_SUCCESS 
            && empty($charge->failure_code)
        );
    }
}