<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->truncate();
        for ($i = 1; $i < 101; $i++) {
            $dataSeeders = [
                'name' => 'product' . $i,
                'amount' => rand(0,1000000),
            ];
            DB::table('products')->insert($dataSeeders);
        }
    }
}
