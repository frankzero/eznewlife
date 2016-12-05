<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Article,App\ArticleMap,App\Log;
use Illuminate\Support\Facades\File;
use App\Media;

class ArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function _spell_link($id, $right_title){
        //抓不到參數，所以寫死的
        $url= "http://eznewlife.com".'/'.$id.'/'.$right_title;
      //  print_r( $_SERVER);
        return $url;
    }
    public function run()
    {
        //
        $faker = Faker::create('zh_TW');
        //  $faker->addProvider(new Faker\Provider\zh_CN\Address($faker));

        //$faker->addProvider(new Faker\Provider\zh_TW\Person($faker));
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');




   //   $articles=Article::where('photo' ,'like','pic_%');//echo "\n count-".$count."\n ";
        $start=6;
        $times=223;
      // $sql= $articles->toSql();
  //    echo $sql; echo $articles->count();
     //  $articles=$articles->get();
        //   foreach ($articles as $s => $article) {
      for ($s=0;$s<$times;$s++) {
            $photo="comic_".sprintf("%03d", ($s+1)).'.jpg';
          $photo_desc='<img src="'.'focus_photos/'.$photo.'" style="width:100%" /><p>'
              .'<img src="'.'focus_photos/'."comic_".sprintf("%03d", ($s+2)).'.jpg'.'" style="width:100%" /><p>'
              .'<img src="'.'focus_photos/'."comic_".sprintf("%03d", ($s+3)).'.jpg'.'" style="width:100%"/><p>'
              .'<img  src="'.'focus_photos/'."comic_".sprintf("%03d", ($s+4)).'.jpg'.'" style="width:100%" /><p>'
          ;
          //  $article->photo=$photo;
         /*  $article->content =$faker->realText().'<p><img src="focus_photos/'.$photo.'">'.$faker->realText();
           $article->save();*/

           $article=Article::create(array(

                'summary' =>		$faker->realText(50),
                'title' =>		"ettoday[test]".$faker->realText(10),
                'content' =>$photo_desc,
                'created_user'=>$faker->numberBetween(1,5),//'1',//$faker->dateTimeBetween('-1 years', '-3 months'),
                'updated_user'=>$faker->numberBetween(1,5),//'1',//$faker->dateTimeBetween('-2 months', '-1 months'),
                'publish_at'=>$faker->dateTimeBetween('-1 days','+0 days'),
                'created_user'=>$faker->numberBetween(1,5),//$faker->numberBetween(1,10),
                'photo'=>'',
                'status'=>'1',
                'category_id'=>10,


            ));

            $article_map=new ArticleMap();
            $article_map->articles_id=$article->id;
            $article_map->site_id=1;
            //$article_map->unique_id=$unique_id =ArticleMap::max('unique_id')+1;
            $article_map->save();
           $article_map->unique_id=$article_map->id;//$unique_id=ArticleMap::max('unique_id')+1;
           $article_map->save();
          $article_map=new ArticleMap();
          $article_map->articles_id=$article->id;
          $article_map->site_id=6;
          //$article_map->unique_id=$unique_id =ArticleMap::max('unique_id')+1;
          $article_map->save();
          $article_map->unique_id=strtolower(str_random(10));//$unique_id=ArticleMap::max('unique_id')+1;
          $article_map->save();

          echo $article_map->id."\n";
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
