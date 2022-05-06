<?php

namespace Database\Seeders;

use App\Status;
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
            ['name' => 'Belum Dikerjakan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sedang Dikerjakan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Selesai', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
