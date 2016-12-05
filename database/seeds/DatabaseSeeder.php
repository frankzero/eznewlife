<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
       // $this->call(ArticleSourceTableSeeder::class);
  $this->call(ArticleTableSeeder::class);
    //    $this->call(RatingTableSeeder::class);
      //  $this->call(AdvTableSeeder::class);
     //   ArticleTableSeeder.php
        //$this->call(EmailTableSeeder::class);
   //  $this->call(AnimationTableSeeder::class);

     //  $this->call(ArticleMapTableSeeder::class);
      //  $this->call(ParameterTableSeeder::class);
        //$this->call(UserTableSeeder::class);
        //$this->call(CategoryTableSeeder::class);
        $this->command->info('User table seeded!');
        Model::reguard();
    }
}
