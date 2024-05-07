<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            "name" => "client4243353232",
            "firstName" => "client",
            "lastName" => "staff",
            "identity" => "2494399593353",
            "phone" => "08011223344",
            "email" => "client@gmail.com",
            "password" => Hash::make("pass@client"),
            "status" => "approved",
        ]);
 
        DB::table("users")->insert([
            "group_id" => "1",
            "name" => "client24243353299",
            "firstName" => "client2",
            "lastName" => "staff",
            "identity" => "2494399593300",
            "phone" => "08011223551",
            "email" => "client2@gmail.com",
            "password" => Hash::make("pass@client"),
            "status" => "approved",
        ]);
 
        DB::table("users")->insert([
            "group_id" => "2",
            "name" => "homecare4243353222",
            "firstName" => "homecare",
            "lastName" => "worker",
            "identity" => "2494399596654",
            "phone" => "08011223554",
            "email" => "worker@gmail.com",
            "password" => Hash::make("pass@worker"),
            "status" => "approved",
        ]);
    }
}
