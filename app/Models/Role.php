<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory, HasUuid;
    protected $guarded = ['id'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}
