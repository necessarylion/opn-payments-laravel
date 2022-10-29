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
        $keys         = new OpnPaymentsKeys();
        $keys->public = config('opn-payments.public_key');
        $keys->secret = config('opn-payments.secret_key');
        return $keys;
    }

    public static function mode() {
        return config('opn-payments.mode');
    }
}