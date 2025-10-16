<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory, HasUuid;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['name', 'description', 'price', 'duration_days', 'features', 'is_active'];
    protected $casts = ['features' => 'array'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }
}
