<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category_id',
    ];

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function childrenCategories(): HasMany
    {
        return $this->hasMany(Category::class)->with('categories');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
