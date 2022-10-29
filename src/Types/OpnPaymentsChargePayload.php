<?php
namespace OpnPayments\Types;

use Exception;
use OpnPayments\OpnPayments;

class OpnPaymentsChargePayload {
    public $amount;
    public $currency;
    public $returnUri;
    public $sourceId = null;
    public $cardId   = null;
    public $ip;
    public $customerId;
    public ?OpnPaymentsCardPayload $card = null;
    public $description;
    public Array $metaData = [];

    public function toArray() {
        if (empty($this->sourceId) 
          && empty($this->customerId)
          && empty($this->card)
          && empty($this->cardId)
        ) {
            throw new Exception('Either source ID, card ID, customer ID or card must be applied');
        }

        $payload = [
            'amount'      => $this->castAmount(),
            'currency'    => $this->currency,
            'return_uri'  => $this->returnUri,
            'description' => $this->description,
            'meta_data'   => $this->metaData,
            'customer'    => $this->customerId,
            'ip'          => $this->ip,
        ];
        if (!empty($this->sourceId)) {
            $payload['source'] = $this->sourceId;
        }
        if (!empty($this->cardId)) {
            $payload['card'] = $this->cardId;
        }
        return $payload;
    }

    public function castAmount() {
        return OpnPayments::castCurrency($this->amount, $this->currency);
    }
}