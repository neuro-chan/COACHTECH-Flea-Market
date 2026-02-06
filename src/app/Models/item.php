<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
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

    /**
     * キーワード検索
     */
    #[Scope]
    protected function search(Builder $query, ?string $keyword): void
    {
        if (empty($keyword)) {
            return;
        }

        $escapedKeyword = Str::replace(
            ['\\', '%', '_'],
            ['\\\\', '\\%', '\\_'],
            $keyword
        );

        $query->where('title', 'like', "%{$escapedKeyword}%");
    }

    /**
     * おすすめタブ用
     */
    #[Scope]
    protected function recommendFor(Builder $query, ?User $user): void
    {
        if ($user) {
            $query->where('seller_id', '!=', $user->id);
        }
    }

    /**
     * マイリストタブ用
     */
    #[Scope]
    protected function mylistFor(Builder $query, User $user): void
    {
        $query->whereHas('likes', fn($q) => $q->where('user_id', $user->id))
              ->where('seller_id', '!=', $user->id);
    }

    /**
     * ログイン中のユーザーが購入した商品
     */
    #[Scope]
    protected function purchasedBy(Builder $query, User $user): void
    {
        $query->whereHas('purchase', fn($q) => $q->where('buyer_id', $user->id));
    }

    /**
     * ログイン中のユーザーが出品した商品
     */
    #[Scope]
    protected function sellingBy(Builder $query, User $user): void
    {
        $query->where('seller_id', $user->id);
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
