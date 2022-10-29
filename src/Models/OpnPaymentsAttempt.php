<?php
namespace OpnPayments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use OpnPayments\OpnPayments;

class OpnPaymentsAttempt extends Model {
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'meta_data'          => 'array',
        'payment_methods'    => 'array',
        'manual_capture'     => 'boolean',
        'test_mode'          => 'boolean',
        'payment_successful' => 'boolean',
    ];

    protected $appends = ['authorized_uri', 'opn_amount'];

    public function charges() {
        return $this->hasMany(OpnPaymentsCharge::class);
    }

    public function charge($paymentMethod = null) {
        $query = $this->charges()->orderBy('id', 'desc');
        if (!empty($paymentMethod)) {
            $query->where('payment_method', $paymentMethod);
        }
        return $query->first();
    }

    protected function amount(): Attribute {
        return Attribute::make(
            get: fn ($value, $attr) => $attr['amount'] / 100 ,
            set: fn ($value) => $value * 100,
        );
    }

    protected function opnAmount(): Attribute {
        return Attribute::make(
            get: fn ($value, $attr) => OpnPayments::castCurrency($attr['amount'] / 100, $attr['currency']) ,
        );
    }

    public function authorizedUri(): Attribute {
        return Attribute::make(
            get: fn ($value, $attr) => config('app.url') . '/opn-payments/' . $attr['order_id'] ,
        );
    }
}
