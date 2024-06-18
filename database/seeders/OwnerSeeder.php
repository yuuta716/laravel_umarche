<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("owners")->insert([
            [
                "name" => "test1",
                "email" => "test1@test.com",
                "password" => Hash::make("Password123"),
                "created_at" => "2023/12/05 11:11:11",
                "updated_at" => "2023/12/06 11:11:11"
            ],

            [
                "name" => "test2",
                "email" => "test2@test.com",
                "password" => Hash::make("Password123"),
                "created_at" => "2023/12/05 11:11:11",
                "updated_at" => "2023/12/06 11:11:11"
            ],

            [
                "name" => "test3",
                "email" => "test3@test.com",
                "password" => Hash::make("Password123"),
                "created_at" => "2023/12/05 11:11:11",
                "updated_at" => "2023/12/06 11:11:11"
            ],
        ]);
    }
}
