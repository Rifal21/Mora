<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasUuid, HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'user_id',
        'plan_id',
        'invoice_number',
        'amount',
        'payment_method',
        'status',
        'doku_response',
        'payment_url',
        'paid_at',
    ];
    protected $casts = ['doku_response' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

}
