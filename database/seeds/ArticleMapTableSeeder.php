<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Article;
use App\ArticleMap;
use Illuminate\Support\Facades\File;
use App\Media;

use Intervention\Image\ImageManagerStatic as Image;


class ArticleMapTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
       // DB::enableQueryLog();
        /*
        DB::listen(function($sql, $bindings, $time) {
            var_dump($sql);
            var_dump($bindings);
            var_dump($time);
            //print_r($sql)."\n".$time."\n";
        });*/
        $articles = DB::table('articles')
            ->leftJoin('articles_map', 'articles.id', '=', 'articles_map.articles_id')
            ->select('articles.id','articles_map.articles_id','articles_map.unique_id');
       $sql= $articles->toSql();
        echo $sql;

        $articles=$articles   ->get();


        //DB::disableQueryLog();
        /*
        //not work
        // select `articles`.`id`, `articles_map`.`articles_id`, `articles_map`.`unique_id` from `articles` left join `articles_map` on (`articles`.`id` = `articles_map`.`articles_id` and `articles_map`.`unique_id` is null)

        $articles = DB::table('articles')

            ->leftJoin('articles_map', function($leftJoin)
            {
                $leftJoin->on( 'articles.id', '=', 'articles_map.articles_id')
                    ->whereNull('articles_map.unique_id');
            })
            ->select('articles.id','articles_map.articles_id','articles_map.unique_id')
            ->get();*/

       // $articles=Article::with('ez_map')->orderBy('id','asc')->get();
        foreach ($articles as $k=>$article){

            if (empty($article->articles_id)) {
                echo $article->id."\n";
                ArticleMap::create(array(

                    'unique_id' =>  ArticleMap::max('unique_id') + 1,
                    'articles_id' =>$article->id,
                    'site_id' => 1


                ));
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
