## Opn Payment Laravel

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

3. ***Register Event Listener (optional)***
Register `OpnPaymentCompleted` class in `app/Providers/EventServiceProvider.php` to handle your order, sending email etc...
```php
public function boot()
{
    ....
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

#### Development

```json
{
    ....
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
    ....
    "necessarylion/opn-payments-laravel" : "@dev"
},
```