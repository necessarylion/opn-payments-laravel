<?php
namespace OpnPayments;

$mode = config('opn-payments.mode');
define('OMISE_PUBLIC_KEY', config('opn-payments.' . $mode . '.public_key'));
define('OMISE_SECRET_KEY', config('opn-payments.' . $mode . '.secret_key'));
define('OMISE_API_VERSION', '2017-11-02');

use OmiseAccount;
use OmiseBalance;
use OmiseCapabilities;
use OmiseCard;
use OmiseChain;
use OmiseCharge;
use OmiseCustomer;
use OmiseDispute;
use OmiseEvent;
use OmiseForex;
use OmiseLink;
use OmiseOccurrence;
use OmiseReceipt;
use OmiseRefund;
use OmiseSchedule;
use OmiseSearch;
use OmiseSource;
use OmiseToken;
use OmiseTransaction;
use OmiseTransfer;
use OpnPayments\Traits\AvailablePaymentMethods;
use OpnPayments\Traits\OpnPaymentsChargeHelper;
use OpnPayments\Traits\OpnPaymentsKeysHelper;
use OpnPayments\Traits\OpnPaymentsPayJsHelper;
use OpnPayments\Types\OpnPaymentsCurrency;
use OpnPayments\Utils\StaticClassConverter;

class OpnPayments {
    use AvailablePaymentMethods;
    use OpnPaymentsKeysHelper;
    use OpnPaymentsChargeHelper;
    use OpnPaymentsPayJsHelper;

    private static $_instance;

    public static function init() {
        if (!self::$_instance) {
            self::$_instance = new OpnPayments();
        }
        return self::$_instance;
    }

    /**
     * @return OmiseCharge
     */
    public static function charge() {
        return new StaticClassConverter(OmiseCharge::class);
    }

    /**
     * @return OmiseAccount
     */
    public static function account() {
        return new StaticClassConverter(OmiseAccount::class);
    }

    /**
     * @return OmiseCapabilities
     */
    public static function capability() {
        return new StaticClassConverter(OmiseCapabilities::class);
    }

    /**
     * @return OmiseCard
     */
    public static function card() {
        return new StaticClassConverter(OmiseCard::class);
    }

    /**
     * @return OmiseLink
     */
    public static function link() {
        return new StaticClassConverter(OmiseLink::class);
    }

    /**
     * @return OmiseChain
     */
    public static function chain() {
        return new StaticClassConverter(OmiseChain::class);
    }

    /**
     * @return OmiseEvent
     */
    public static function event() {
        return new StaticClassConverter(OmiseEvent::class);
    }

    /**
     * @return OmiseToken
     */
    public static function token() {
        return new StaticClassConverter(OmiseToken::class);
    }

    /**
     * @return OmiseForex
     */
    public static function forex() {
        return new StaticClassConverter(OmiseForex::class);
    }

    /**
     * @return OmiseRefund
     */
    public static function refund() {
        return new StaticClassConverter(OmiseRefund::class);
    }

    /**
     * @return OmiseSearch
     */
    public static function search() {
        return new StaticClassConverter(OmiseSearch::class);
    }

    /**
     * @return OmiseSource
     */
    public static function source() {
        return new StaticClassConverter(OmiseSource::class);
    }

    /**
     * @return OmiseBalance
     */
    public static function balance() {
        return new StaticClassConverter(OmiseBalance::class);
    }

    /**
     * @return OmiseOccurrence
     */
    public static function occurrence() {
        return new StaticClassConverter(OmiseOccurrence::class);
    }

    /**
     * @return OmiseCustomer
     */
    public static function customer() {
        return new StaticClassConverter(OmiseCustomer::class);
    }

    /**
     * @return OmiseSchedule
     */
    public static function schedule() {
        return new StaticClassConverter(OmiseSchedule::class);
    }

    /**
     * @return OmiseTransfer
     */
    public static function transfer() {
        return new StaticClassConverter(OmiseTransfer::class);
    }

    /**
     * @return OmiseReceipt
     */
    public static function receipt() {
        return new StaticClassConverter(OmiseReceipt::class);
    }

    /**
     * @return OmiseDispute
     */
    public static function dispute() {
        return new StaticClassConverter(OmiseDispute::class);
    }

    /**
     * @return OmiseTransaction
     */
    public static function transaction() {
        return new StaticClassConverter(OmiseTransaction::class);
    }

    public static function castCurrency($amount, $currency) {
        if (strtoupper($currency) == OpnPaymentsCurrency::JAPANESE_YEN) {
            return $amount;
        } else {
            return round($amount * 100, 2);
        }
    }
}
