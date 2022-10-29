<?php
namespace OpnPayments;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use OpnPayments\Controllers\RedirectUrlController;

class OpnPaymentsServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        $this->publishes([
            __DIR__ . '/../config/opn-payments.php'                                    => config_path('opn-payments.php'),
            __DIR__ . '/../views/payment.blade.php'                                    => base_path('resources/views/payment.blade.php'),
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

        Route::get('/opn-payments/methods/{amount}/{currency}', [RedirectUrlController::class, 'payJsForm']);

        Route::get('/opn-payments/{orderId}', function($orderId) {
            $amount   = 1000;
            $currency = 'THB';
            return view('payment', [
                'amount'   => $amount, 
                'currency' => $currency,
                'backUrl'  => '#',
                'theme'    => OpnPayments::getTheme(),
            ]);
        });
    }
}
