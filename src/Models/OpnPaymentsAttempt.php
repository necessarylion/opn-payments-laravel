<?php
namespace OpnPayments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
