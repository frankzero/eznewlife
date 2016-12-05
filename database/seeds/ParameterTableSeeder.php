<?php

use Illuminate\Database\Seeder;
use App\Parameter;
class ParameterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Parameter::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        //$animations=Animation::orderBy('id','asc')->get();

        Parameter::create(array(


                'name'=>'fb_rate',
                'domain'=>'getez.info',
                'data'=>80,
                'description'=>'「點我看文章」機率 - 廣告',
                'created_user'=>1,
                'updated_user'=>1,



            ));

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
