<?php
namespace OpnPayments;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use OpnPayments\Controllers\PaymentController;

class OpnPaymentsServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        $this->publishes([
            __DIR__ . '/../config/opn-payments.php'      => config_path('opn-payments.php'),
            __DIR__ . '/Listeners/OpnPaymentHandler.php' => app_path('Listeners/OpnPaymentHandler.php'),
            __DIR__ . '/../views/opn-payment.blade.php'  => base_path('resources/views/opn-payment.blade.php'),
            __DIR__ . '/../views/opn-success.blade.php'  => base_path('resources/views/opn-success.blade.php'),
            __DIR__ . '/../views/opn-failed.blade.php'   => base_path('resources/views/opn-failed.blade.php'),

            __DIR__ . '/../views/assets/opn-script.js' => public_path('opn-payments/opn-script.js'),
            __DIR__ . '/../views/assets/opn-style.css' => public_path('opn-payments/opn-style.css'),

            __DIR__ . '/../migrations/2022_10_28_190105_create_opn_payments_table.php' => base_path('database/migrations/2022_10_28_190105_create_opn_payments_table.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton(OpnPayments::class, function() {
            return OpnPayments::init();
        });

        Route::get('/opn-payments/methods/{orderId}', [PaymentController::class, 'payJsForm']);
        Route::get('/opn-payments/{orderId}',  [PaymentController::class, 'renderPayment']);
        Route::get('/opn-payments/status/{orderId}',  [PaymentController::class, 'status']);
        Route::get('/opn-payments/success/{orderId}',  [PaymentController::class, 'success']);
        Route::get('/opn-payments/failed/{orderId}',  [PaymentController::class, 'failed']);
        Route::post('/opn-payments/charge/{orderId}',  [PaymentController::class, 'charge']);
        Route::any('/opn-payments/complete/{orderId}',  [PaymentController::class, 'complete']);
    }
}
