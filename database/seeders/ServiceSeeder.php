<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::insert([
            ['name' => 'Cuci', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Setrika', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
