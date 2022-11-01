# Opn Payment Laravel

[![php](https://badgen.net/badge/icon/php?icon=packagist&label=Laravel&nbsp;Package)](https://packagist.org/packages/necessarylion/opn-payments-laravel) ![php](https://badgen.net/badge/Opn&nbsp;Payments/Laravel/red)

This package is [Opn Payments (former name Omise)](https://opn.ooo) Laravel package using omise-php sdk. This package will help to generate redirect url with ready made payment form. You will only need to write few lines of code to create payment in your Laravel project. This package will handle all creating charge, handling complete payment automatically. We made it very easy to integrate payment system in your project. 


<img src="https://raw.githubusercontent.com/necessarylion/opn-payments-laravel/master/preview-desktop.png" alt="drawing" height="300"/> <img src="https://raw.githubusercontent.com/necessarylion/opn-payments-laravel/master/preview-mobile.png" alt="drawing" height="300"/>


## Installation

1. **Install Package**
```bash
composer require necessarylion/opn-payments-laravel
```

2. **Generate vendor**
```bash
php artisan vendor:publish --provider="OpnPayments\OpnPaymentsServiceProvider" --force
```

3. **Run Migration**
```bash
php artisan migrate
```

4. **Register Event Listener (optional)**
- Register `OpnPaymentCompleted` class in `app/Providers/EventServiceProvider.php`\
to handle your order, sending email etc...

```php
public function boot()
{
    ...
    Event::listen(
        \OpnPayments\Events\OpnPaymentCompleted::class,
        [\App\Listeners\OpnPaymentHandler::class, 'handle']
    );
}
```

5. **Add credentials in .env file**

```bash
OPN_MODE=test  # test or live
OPN_TEST_PUBLIC_KEY=pkey_***
OPN_TEST_SECRET_KEY=skey_***
OPN_LIVE_PUBLIC_KEY=pkey_***
OPN_LIVE_SECRET_KEY=skey_***
```
- Make sure that `APP_URL` include port if you are running on port
- Example

```bash
APP_URL=http://localhost:8000
```

## Create Charge
Create charge using redirect url function

```php
$payload = new OpnPaymentsRedirectPayload();
$payload->amount = 1000;
$payload->currency = OpnPaymentsCurrency::THAILAND_BAHT;
$payload->cancelUri = 'http://localhost:8000';
$payload->redirectUri = 'http://localhost:8000';
$payload->orderId = Str::uuid();
$payload->locale = OpnPaymentsLocale::ENGLISH;
$payload->paymentMethods = OpnPayments::paymentMethods();

return redirect(OpnPayments::getRedirectUrl($payload)->authorized_uri);
```

| **Fields**     | **Type** | **Description**                                                                                                                                     |
|----------------|----------|-----------------------------------------------------------------------------------------------------------------------------------------------------|
| `amount`         | `int`      | Amount to charge                                                                                                                                    |
| `currency`       | `string`   | Currency of the amount eg. THB, SGD, RGN. You can use `OpnPaymentsCurrency` helper class for this field                                             |
| `cancelUri`      | `string`   | Url to redirect back if the user cancel payment                                                                                                     |
| `redirectUri`    | `string`   | Url to redirect back if the payment completed                                                                                                       |
| `orderId`       | `string`   | Unique order Id.                                                                                                                                    |
| `paymentMethods` | `array`    | Array of payment methods.  You can see list of supported methods [here](https://www.omise.co/omise-js#supported-payment-methods-for-pre-built-form) |
| `locale`         | `string`   | Language, such ash , en, th, ja. You can use `OpnPaymentsLocale` helper class for this field                                                        |
| `metaData`       | `array`    | Extra meta data to append.                                                                                                                          |

## Product List
If you want to show list of products in payment page, you can do as below.

*Single Product*
```php
$payload->metaData = [
    'product' => [
        'image' => 'https://placehold.jp/75767a/ffffff/150x150.png'
        'name' => 'I Phone',
        'quantity' => '1',
        'price' => '320000',
    ]
];
```

*Multiple Product*

```php
$payload->metaData = [
    'products' => [
        [
            'image' => 'https://placehold.jp/75767a/ffffff/150x150.png'
            'name' => 'I Phone',
            'quantity' => '1',
            'price' => '320000',
        ]
    ]
];
```

## Event Listener

**Handle Completed Payment using `OpnPaymentHandler` Listener**\
\
*in `app/Listeners/OpnPaymentHandler.php`, you can check the `payment_successful` status of payment attempt.*\
*to handle order, sending email etc..*

```php
public function handle(OpnPaymentCompleted $event) {
     $attempt = $event->attempt;
    $charge = $event->charge;
    if ($attempt->payment_successful) {
        // handle payment success here
    } else {
        // handle payment failed here
    }
}
```

## Scheduler
Register scheduler for pending charges.

*This scheduler will get all pending charge from records withing 24 hours. Then it will fetch status from Opn API and update if success or failed.*

In `app/Console/Kernel.php` inside `schedule()` function add below line.
```php
$schedule->command('opn-payments-scheduler')->everyFiveMinutes();
```

## Contribute
Want to contribute? Great! Fork the repo and create PR to us.

## Development Process

- In your Laravel project
- Create packages folder `mkdir packages`
- And clone our package `git clone git@github.com:necessarylion/opn-payments-laravel.git`

```json
{
    ...
    "repositories": {
        "opn-payments-laravel" : {
            "type": "path",
            "url" : "packages/opn-payments-laravel",
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
```bash
composer update
```
