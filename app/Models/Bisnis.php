<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bisnis extends Model
{
    /** @use HasFactory<\Database\Factories\BisnisFactory> */
    use HasFactory, HasUuid;
    protected $guarded = ['id'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
