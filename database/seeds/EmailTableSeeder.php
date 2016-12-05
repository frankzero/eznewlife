<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Email;
class EmailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create('zh_TW');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        for($i=0;$i<10;$i++)
        {
            Email::create(array(

                'name' =>		$faker->userName,
                'email' =>$faker->email,
                'adddate'=>$faker->dateTime()




        ));
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

}
