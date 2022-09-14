<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Item::insert([
            ['name' => 'Baju', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Celana', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
