<?php
namespace OpnPayments\Types;

use Exception;
use Illuminate\Support\Str;

class OpnPaymentsChargePayload {
    public $amount;
    public $currency;
    public $returnUri;
    public $sourceId = null;
    public $cardId   = null;
    public $ip;
    public $customerId;
    public $token                        = null;
    public ?OpnPaymentsCardPayload $card = null;
    public $description;
    public Array $metaData = [];

    public function toArray() {
        if (empty($this->sourceId) 
          && empty($this->customerId)
          && empty($this->card)
          && empty($this->cardId)
          && empty($this->token)
        ) {
            throw new Exception('Either source ID, card ID, customer ID, card  or token must be applied');
        }

        $payload = [
            'amount'      => $this->amount,
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
        if (!empty($this->token)) {
            if (Str::startsWith($this->token, 'tokn_')) {
                $payload['card'] = $this->token;
            } else {
                $payload['source'] = $this->token;
            }
        }
        return $payload;
    }
}