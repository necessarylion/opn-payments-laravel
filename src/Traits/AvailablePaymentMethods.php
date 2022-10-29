<?php
namespace OpnPayments\Traits;

use OpnPayments\OpnPayments;

trait AvailablePaymentMethods {
    protected static $_unavailableMethods = ['barcode_alipay'];

    /**
     * get available payment method arrays
     *
     * @return Array
     */
    public static function paymentMethods() {
        $result  = OpnPayments::capability()->retrieve();
        $keys    = [];
        $methods = $result->payment_backends;
        foreach ($methods as $method) {
            foreach ($method as $key => $value) {
                if (!in_array($key, self::$_unavailableMethods)) {
                    $keys[] = $key;
                }
            }
        }
        return $keys;
    }
}