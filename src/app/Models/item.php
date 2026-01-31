<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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


    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes')
            ->withPivot('created_at');
    }


    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany
    {
    return $this->hasMany(Like::class);
    }

    public function purchase(): HasOne
    {
        return $this->hasOne(Purchase::class);
    }

    /**
     * ItemがSoldかどうかを判定
     * コントローラーで with('purchase') で事前ロード
     */
    protected function isSold(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->relationLoaded('purchase')
                ? $this->purchase !== null
                : $this->purchase()->exists()
        );
    }
}
