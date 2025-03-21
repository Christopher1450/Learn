<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            // General Book Categories
            ['name' => 'Science Fiction'],
            ['name' => 'Fantasy'],
            ['name' => 'Romance'],
            ['name' => 'Horror'],
            ['name' => 'Mystery'],
            ['name' => 'Thriller'],
            ['name' => 'Historical Fiction'],
            ['name' => 'Biography'],
            ['name' => 'Self-Help'],
            ['name' => 'Philosophy'],
            ['name' => 'Psychology'],
            ['name' => 'Business'],
            ['name' => 'Technology'],
            ['name' => 'Mathematics'],
            ['name' => 'Science'],
            ['name' => 'Health & Fitness'],
            ['name' => 'Cooking'],
            ['name' => 'Poetry'],
            ['name' => 'Drama'],
            ['name' => 'Young Adult'],
            ['name' => 'Adventure'],
            ['name' => 'Graphic Novels'],
            ['name' => 'Light Novels'],

            // Manhua & Manga Categories
            ['name' => 'Reicarnated'],
            ['name' => 'Action'],
            ['name' => 'Time Travel'],
            ['name' => 'Comedy'],
            ['name' => 'Drama'],
            ['name' => 'Fantasy'],
            ['name' => 'Romance'],
            ['name' => 'Horror'],
            ['name' => 'Mystery'],
            ['name' => 'Thriller'],
            ['name' => 'Historical'],
            ['name' => 'Adventure'],
            ['name' => 'Psychological'],
            ['name' => 'Supernatural'],
            ['name' => 'School'],
            ['name' => 'Sports'],
            ['name' => 'Sci-Fi'],
            ['name' => 'Slice of Life'],
            ['name' => 'Mecha'],
            ['name' => 'Music'],
            ['name' => 'Martial Arts'],
            ['name' => 'Military'],
            ['name' => 'Magic'],
            ['name' => 'Isekai'],
            ['name' => 'Demons'],
            ['name' => 'Vampires'],
            ['name' => 'Zombies'],
            ['name' => 'Tragedy'],  
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
