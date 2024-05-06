<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->insert([
            "group_id" => "1",
            "name" => "4243353232",
            "firstName" => "client",
            "lastName" => "staff",
            "identity" => "2494399593353",
            "phone" => "08011223344",
            "email" => "client@gmail.com",
            "password" => bcrypt("pass@client"),
            "status" => "approved",
        ]);
 
        DB::table("users")->insert([
            "group_id" => "1",
            "name" => "4243353299",
            "firstName" => "client2",
            "lastName" => "staff",
            "identity" => "2494399593300",
            "phone" => "0801122355",
            "email" => "client2@gmail.com",
            "password" => bcrypt("pass@client"),
            "status" => "approved",
        ]);
 
        DB::table("users")->insert([
            "group_id" => "2",
            "name" => "4243353222",
            "firstName" => "homecare",
            "lastName" => "worker",
            "identity" => "2494399596654",
            "phone" => "0801122355",
            "email" => "worker@gmail.com",
            "password" => bcrypt("pass@worker"),
            "status" => "approved",
        ]);
    }
}
