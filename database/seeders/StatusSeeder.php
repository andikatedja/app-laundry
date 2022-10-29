<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::insert([
            ['id' => 1, 'name' => 'Belum Dikerjakan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Sedang Dikerjakan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Selesai', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
