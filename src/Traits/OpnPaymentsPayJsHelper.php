<?php
namespace OpnPayments\Traits;

use Exception;
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
        if (!$source) {
            return null;
        }
        $scan = $source->scannable_code;
        if ($scan && $scan->image) {
            $qrCode = $scan->image->download_uri;
            return $qrCode;
        }
        return null;
    }

    public static function getQRContent($qrCode) {
        try {
            return file_get_contents($qrCode);
        }
        catch(Exception $e) {
            return null;
        }
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

    public static function currencyCast($value, $decimal = 0) {
        if ($decimal == 0) {
          $value = number_format((float) $value, 2, '.', ',');
          $value = rtrim($value, '0');
          $value = rtrim($value, '.');
        } else {
          $value = number_format((float) $value, 2, '.', ',');
        }
        return $value;
    }
}