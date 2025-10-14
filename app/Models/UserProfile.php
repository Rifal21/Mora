<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    /** @use HasFactory<\Database\Factories\UserProfileFactory> */
    use HasFactory, HasUuid;
    protected $guarded = ['id'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
