<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    /** @use HasFactory<\Database\Factories\BlogPostFactory> */
    use HasFactory, HasUuid;
    protected $guarded = ['id'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id', 'id');
    }
}
