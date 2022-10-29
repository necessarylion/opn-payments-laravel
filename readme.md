## Opn Payment Laravel

![php](https://badgen.net/badge/icon/php?icon=packagist&label=Laravel&nbsp;Package) ![php](https://badgen.net/badge/Opn&nbsp;Payments/Laravel/red)

This package is opn payments Laravel package using omise-php sdk. This package will help to generate redirect url with ready made payment form. You will only need to write few lines of code to create payment in your Laravel project. This package will handle all creating charge, handling complete payment automatically. We made it very easy to integrate payment system in your project. 

#### Installation

1. ***Install Package***
```sh
composer require necessarylion/opn-payments-laravel
```
2. ***Generate vendor***
```php
php artisan vendor:publish --provider="OpnPayments\OpnPaymentsServiceProvider" --force
```
3. ***Run Migration***
```php
php artisan migrate
```

4. ***Register Event Listener (optional)***
- Register `OpnPaymentCompleted` class in `app/Providers/EventServiceProvider.php` to handle your order, sending email etc...
```php
public function boot()
{
    ...
    Event::listen(
        OpnPaymentCompleted::class,
        [\App\Listeners\OpnPaymentHandler::class, 'handle']
    );
}
```

#### Create charge with redirect url

```php
$payload = new OpnPaymentsRedirectPayload();
$payload->amount = 1000;
$payload->currency = OpnPaymentsCurrency::THAILAND_BAHT;
$payload->cancelUri = 'https://example.com';
$payload->redirectUri = 'https://example.com';
$payload->orderId = Str::uuid();
$payload->locale = OpnPaymentsLocale::ENGLISH;
$payload->paymentMethods = OpnPayments::paymentMethods();

return redirect(OpnPayments::getRedirectUrl($payload)->authorized_uri);
```

#### Handle payment completed

- in `app/Listeners/OpnPaymentHandler.php`, you can check the status of payment attempt.
- then you can do handling your order, sending email etc..

```php
public function handle(OpnPaymentCompleted $event) {
    $attempt = $event->attempt;
    if ($attempt->payment_successful) {
        // handle payment success here
    } else {
        // handle payment failed here
    }
}
```

- to get latest charge of payment attempt, you can do

```php
$charge = $attempt->charge();
$opnChargeId = $charge->charge_id;
```

#### Development

```json
{
    ...
    "repositories": {
        "opn-payments-laravel" : {
            "type": "path",
            "url" : "packages/omise-laravel",
            "options": {
                "symlink": true
            }
        }
    },
}
```

```json
"require": {
    ...
    "necessarylion/opn-payments-laravel" : "@dev"
},
```