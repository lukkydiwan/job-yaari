<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'title', 'slug', 'short_description', 'content',
        'image', 'category_id', 'tags', 'published_at'
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getTagsArrayAttribute()
    {
        return $this->tags ?? [];
    }
}
