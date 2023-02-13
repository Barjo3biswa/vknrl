<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CasteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("castes")->insert([
            [
                "name" => "General",
                "created_at"    =>current_date_time(),
                "updated_at"    =>current_date_time(),
            ],
            [
                "name" => "SC",
                "created_at"    =>current_date_time(),
                "updated_at"    =>current_date_time(),
            ],
            [
                "name" => "ST",
                "created_at"    =>current_date_time(),
                "updated_at"    =>current_date_time(),
            ],
            [
                "name" => "OBC/MOBC",
                "created_at"    =>current_date_time(),
                "updated_at"    =>current_date_time(),
            ],
        ]);
    }
}
