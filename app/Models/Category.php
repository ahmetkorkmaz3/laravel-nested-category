<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Category extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'name',
        'category_id',
    ];

    protected $appends = ['total_product_count'];

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function childrenCategories(): HasMany
    {
        return $this->hasMany(Category::class, 'category_id')
            ->with('childrenCategories')
            ->withCount('products');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function descendants(): Collection
    {
        $collection = new Collection();

        foreach ($this->childrenCategories as $child) {
            $collection->add($child);
            $collection = $collection->merge($child->descendants());
        }

        return $collection;
    }

    public function getTotalProductCountAttribute(): int
    {
        return $this->descendants()->add($this)->sum('products_count');
    }
}
