<?php
namespace OpnPayments\Types;

class OpnPaymentsRedirectPayload {
    public int $amount;
    public $orderId;
    public $currency              = 'thb';
    public ?Array $paymentMethods = ['credit_card'];
    public ?Array $metaData       = [];
    public $language              = 'en';
    public $redirectUri;
    public $cancelUri;
}