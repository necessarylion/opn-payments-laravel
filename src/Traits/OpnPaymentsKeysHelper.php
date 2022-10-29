<?php
namespace OpnPayments\Traits;

use OpnPayments\Types\OpnPaymentsKeys;

trait OpnPaymentsKeysHelper {
    /**
     * get available payment method arrays
     *
     * @return OpnPaymentsKeys
     */
    public static function key() {
        $mode         = config('opn-payments.mode');
        $keys         = new OpnPaymentsKeys();
        $keys->public = config('opn-payments.' . $mode . '.public_key');
        $keys->secret = config('opn-payments.' . $mode . '.secret_key');
        return $keys;
    }

    public static function mode() {
        return config('opn-payments.mode');
    }
}