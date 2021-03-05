<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Category::factory(10)->create()->each(function ($category) {
            $category->childrenCategories()->saveMany(Category::factory(6)->create())->each(function ($subCategory) {
                $subCategory->childrenCategories()->saveMany(Category::factory(4)->create());
            });
        });

        Product::factory(100000)->create();

    }
}
