<?php
namespace OpnPayments\Traits;

use OpnPayments\Models\OpnPaymentsAttempt;
use OpnPayments\OpnPayments;
use OpnPayments\Types\OpnPaymentsChargePayload;
use OpnPayments\Types\OpnPaymentsRedirectPayload;

trait OpnPaymentsChargeHelper {

    /**
     * @param OpnPaymentsRedirectPayload $payload
     * @return OpnPaymentsAttempt
     */
    public static function getRedirectUrl(OpnPaymentsRedirectPayload $payload) {
        $attempt                  = new OpnPaymentsAttempt();
        $attempt->order_id        = $payload->orderId;
        $attempt->test_mode       = OpnPayments::mode() == 'test' ? true : false;
        $attempt->payment_methods = $payload->paymentMethods;
        $attempt->meta_data       = $payload->metaData;
        $attempt->amount          = $payload->amount;
        $attempt->currency        = $payload->currency;
        $attempt->language        = $payload->language;
        $attempt->redirect_uri    = $payload->redirectUri;
        $attempt->cancel_uri      = $payload->cancelUri;
        $attempt->save();
        return $attempt;
    }

    /**
     * get available payment method arrays
     *
     * @return Array
     */
    public static function createCharge(OpnPaymentsChargePayload $charge) {
        $chargePayload = $charge->toArray();
        if ($charge->card) {
            $cardPayload = $charge->card->toArray();
            $token       = OpnPayments::token()->create(['card' => $cardPayload]);
            $customer    = OpnPayments::customer()->create([
                'email'       => $charge->card->cardHolderEmail ?? $charge->card->cardHolderName,
                'description' => $charge->card->cardHolderName,
                'card'        => $token->id,
            ]);
            if (isset($customer->id) 
              && isset($customer->cards->data) 
              && isset($customer->cards->data[0])
              && isset($customer->cards->data[0]->id)
            ) {
                $cardId = $customer->cards->data[0]->id;
            }
            $chargePayload['customer'] = $customer->id;
            $chargePayload['card']     = $cardId;
        }
        return OpnPayments::charge()->create(self::removeNull($chargePayload));
    }

    public static function removeNull($data) {
        $payload = [];
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $payload[$key] = $value;
            }
        }
        return $payload;
    }
}