<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    private $category;

    public function __constrcut(Category $category_model){
        $this->category = $category_model;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name'          => 'Work',
                'created_at'    => NOW(),
                'updated_at'    => NOW()
            ],
            [
                'name'          => 'Music',
                'created_at'    => NOW(),
                'updated_at'    => NOW()
            ],
            [
                'name'          => 'Hobby',
                'created_at'    => NOW(),
                'updated_at'    => NOW()
            ],
            [
                'name'          => 'Sports',
                'created_at'    => NOW(),
                'updated_at'    => NOW()
            ],
            [
                'name'          => 'Family',
                'created_at'    => NOW(),
                'updated_at'    => NOW()
            ],
            [
                'name'          => 'Friends',
                'created_at'    => NOW(),
                'updated_at'    => NOW()
            ]
        ];

        $this->category->insert($categories);
    }
}
