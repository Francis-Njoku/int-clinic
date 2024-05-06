<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WorkerClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("worker_clients")->insert([
            "user_id" => "1",
            "worker_id" => "3",
            "status" => "approved",
        ]);

        DB::table("worker_clients")->insert([
            "user_id" => "2",
            "worker_id" => "3",
            "status" => "approved",
        ]);
    }
}
