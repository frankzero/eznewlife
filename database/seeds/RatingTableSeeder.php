<?php

use Illuminate\Database\Seeder;
//use DB;
use willvincent\Rateable\Rating;
class RatingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $sql=' SELECT * ,count(1) as rating_count FROM ratings where 1 group by av_user_id,rateable_id ORDER BY rating_count DESC';
        $result=DB::table('ratings')->groupBy('av_user_id', 'rateable_id')->orderBy('rating_count',desc)->select(DB::raw('count(*) as rating_count'),'av_user_id','rateable_id as article_id' )->get();
        //dd($result);
        foreach ($result as $k=>$v){
            if ($v->rating_count>1){
                $rating=Rating::where('av_user_id',$v->av_user_id)->where('rateable_id',$v->article_id)->orderBy('created_at','asc')->first();
                $rating->delete();
            }

        }

    }
}
