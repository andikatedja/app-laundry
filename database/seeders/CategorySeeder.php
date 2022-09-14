<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::insert([
            ['id' => 1, 'name' => 'Satuan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Kiloan', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
