<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\User;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('en_US');
        //  $faker->addProvider(new Faker\Provider\zh_CN\Address($faker));

        //$faker->addProvider(new Faker\Provider\zh_TW\Person($faker));
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

         User::truncate();

        //$faker->addProvider(new Faker\Provider\Lorem($faker));
        $admin=['nom','miu'];
        foreach($admin as $val)
        {
            User::create(array(

                'name' =>$val,
                'email' =>$faker->email,
                'password'=>bcrypt('lucky520'),//$faker->dateTimeBetween('-1 years', '-3 months'),
                'role'=>'A'


            ));
        }
        $user=['akao','celine','circle','cutey537','evelyn','jessy'];
        foreach($user as $val)
        {
            User::create(array(

                'name' =>		$val,
                'email' =>$faker->email,
                'password'=>bcrypt('lucky520'),//$faker->dateTimeBetween('-1 years', '-3 months'),



            ));
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
