<?php
namespace OpnPayments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class OpnPaymentsAttempt extends Model {
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'meta_data'          => 'array',
        'payment_methods'    => 'array',
        'test_mode'          => 'boolean',
        'payment_successful' => 'boolean',
    ];

    protected $appends = ['authorized_uri'];

    public function charges() {
        return $this->hasMany(OpnPaymentsChargeModel::class);
    }

    public function charge($paymentMethod = null) {
        $query = $this->charges()->order('id', 'desc');
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

    public function authorizedUri(): Attribute {
        return Attribute::make(
            get: fn ($value, $attr) => config('app.url') . '/opn-payments/' . $attr['order_id'] ,
        );
    }
}
