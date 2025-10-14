<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, HasUuid;
    protected $guarded = ['id'];
    public $incrementing = false;
    protected $keyType = 'string';
    protected $casts = [
        'images' => 'array', // supaya otomatis decode/encode JSON
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
    public function bisnis()
    {
        return $this->belongsTo(Bisnis::class);
    }
}
