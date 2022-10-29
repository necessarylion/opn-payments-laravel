<?php
namespace OpnPayments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpnPaymentsCharge extends Model {
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'payment_successful' => 'boolean',
    ];
}
