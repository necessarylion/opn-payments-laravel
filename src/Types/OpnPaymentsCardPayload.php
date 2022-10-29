<?php
namespace OpnPayments\Types;

use Exception;

class OpnPaymentsCardPayload {
    public int $expiredMonth;
    public int $expiredYear;
    public String $cardHolderName;
    public $cardHolderEmail;
    public String $cardNumber;
    public int $securityCode;
    public $city;
    public $postalCode;

    public function toArray() {
        if (empty($this->cardHolderName)) {
            throw new Exception('required card holder name');
        }
        return [
            'name'             => $this->cardHolderName,
            'number'           => $this->cardNumber,
            'expiration_month' => $this->expiredMonth,
            'expiration_year'  => $this->expiredYear,
            'city'             => $this->city,
            'postal_code'      => $this->postalCode,
            'security_code'    => $this->securityCode,
        ];
    }
}