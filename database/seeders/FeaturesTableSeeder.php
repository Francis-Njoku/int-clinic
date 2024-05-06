<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class FeaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("features")->insert([
            "user_id" => "1",
            "incidentType" => "Type 1",
            "severity" => "high",
            "identity" => "193532858290990350304",
            "details" => "There are many variations of passages of Lorem Ipsum available,
             but the majority have suffered alteration in some form, by injected humour, 
             or randomised words which don't look even slightly believable. If you are going 
             to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing
              hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend 
              to repeat predefined chunks as necessary, making this the first true generator on the 
              Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model 
              sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem 
              Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.",
            "incidentDate" => "2024-05-04 10:39:20",
            "status" => "approved",
        ]);

        DB::table("features")->insert([
            "user_id" => "2",
            "incidentType" => "Type 3",
            "severity" => "medium",
            "identity" => "193532858290990359945",
            "details" => "It is a long established fact that a reader will be
             distracted by the readable content of a page when looking at its 
             layout. The point of using Lorem Ipsum is that it has a more-or-less 
             normal distribution of letters, as opposed to using 'Content here, 
             content here', making it look like readable English. Many desktop 
             publishing packages and web page editors now use Lorem Ipsum as their 
             default model text, and a search for 'lorem ipsum' will uncover many 
             web sites still in their infancy. Various versions have evolved over 
             the years, sometimes by accident, sometimes on purpose (injected humour 
             and the like)..",
            "incidentDate" => "2024-05-05 13:39:20",
            "status" => "approved",
        ]);

        DB::table("features")->insert([
            "user_id" => "1",
            "incidentType" => "Type 4",
            "severity" => "low",
            "identity" => "193532844440990359945",
            "details" => "The standard chunk of Lorem Ipsum used since the 1500s is reproduced
             below for those interested. Sections 1.10.32 and 1.10.33 from by Cicero are also 
             reproduced in their exact original form, accompanied by English versions from the 
             1914 translation by H. Rackham.",
            "incidentDate" => "2024-05-05 16:39:20",
            "status" => "approved",
        ]);
    }
}
