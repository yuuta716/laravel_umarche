<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class shopseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("shops")->insert([
            [
                "owner_id"=>1,
                "name" => "ここに店名が入ります",
                "information" => "ここに店の情報が入ります",
                "filename" =>"sample1.jpg",
                "is_selling" => true,
            ],

            [
                "owner_id"=>2,
                "name" => "ここに店名が入ります",
                "information" => "ここに店の情報が入ります",
                "filename" =>"sample2.jpg",
                "is_selling" => true,
            ],
        ]);

    }
}
