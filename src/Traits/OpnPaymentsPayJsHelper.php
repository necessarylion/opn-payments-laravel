<?php
namespace OpnPayments\Traits;

trait OpnPaymentsPayJsHelper {
    public static function getTheme() {
        return config('opn-payments.theme');
    }
}