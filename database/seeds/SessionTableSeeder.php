<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Admin;

class SessionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sessions')->delete();
        $admin = Admin::first();
        $insert_data = [];
        $current_year = date("Y");
        for ($i=0; $i < 6; $i++) { 
            $insert_data[] = [
                "name"  => ($current_year+$i)."-".($current_year+$i+1),
                "created_by" => ($admin ? $admin->id : null),
                "created_at"    => date("Y-m-d"),
                "updated_at"    => date("Y-m-d"),
            ];
        }
        DB::table("sessions")->insert($insert_data);
    }
}
