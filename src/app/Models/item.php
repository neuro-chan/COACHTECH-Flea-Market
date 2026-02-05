<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'seller_id',
        'title',
        'description',
        'price',
        'brand_name',
        'image_url',
        'condition_id',
    ];

    // ========================================
    // Relation
    // ========================================

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes')
            ->withPivot('created_at');
    }

    public function purchase(): HasOne
    {
        return $this->hasOne(Purchase::class);
    }

    // ========================================
    // Scope
    // ========================================

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (empty($keyword)) {
            return $query;
        }

        $escapedKeyword = Str::replace(
            ['\\', '%', '_'],
            ['\\\\', '\\%', '\\_'],
            $keyword
        );

        return $query->where('title', 'like', "%{$escapedKeyword}%");
    }

    public function scopeRecommendFor(Builder $query, ?User $user): Builder
    {
        if (!$user) {
            return $query;
        }

        return $query->where('seller_id', '!=', $user->id);
    }

    public function scopeMylistFor(Builder $query, User $user): Builder
    {
        return $query->whereHas('likes', fn($q) => $q->where('user_id', $user->id))
                     ->where('seller_id', '!=', $user->id);
    }

    // ========================================
    // Accessor
    // ========================================
    protected function isSold(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->relationLoaded('purchase')
                ? $this->purchase !== null
                : $this->purchase()->exists()
        );
    }
}
