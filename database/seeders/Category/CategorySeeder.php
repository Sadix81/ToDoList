<?php

namespace Database\Seeders\Category;

use App\Models\Category\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['title' => 'list-1'],
            ['title' => 'list-2'],
            ['title' => 'list-3'],
            ['title' => 'list-4'],
        ];

        Category::insert($categories);

        // foreach($categories as $category){
        //     Category::create($category);
        // }
    }
}
