<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'articles';   // collection f cloud1

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'category',
        'published',
        'author_name',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title')) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function getExcerptAttribute(): string
    {
        return Str::limit($this->description, 120);
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }
}
