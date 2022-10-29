<?php
namespace OpnPayments\Controllers;

use Illuminate\Http\Request;
use OpnPayments\Models\OpnPaymentsAttempt;
use OpnPayments\OpnPayments;

class RedirectUrlController extends Controller {
    public function payJsForm($amount, $currency) {
        $price = OpnPayments::castCurrency($amount, $currency);
        return OpnPayments::getPayJsContent($price, $currency);
    }

    public function getRedirectUrl(Request $request) {
        $request->validate([
            'amount'          => 'required',
            'currency'        => 'required',
            'redirect_uri'    => 'required',
            'order_id'        => 'required',
            'meta_data'       => 'array',
            'payment_methods' => 'array',
        ]);
        $charge                  = new OpnPaymentsAttempt();
        $charge->order_id        = $request->order_id;
        $charge->test_mode       = OpnPayments::mode() == 'test' ? true : false;
        $charge->payment_methods = $request->payment_methods;
        $charge->meta_data       = $request->meta_data;
    }
}
