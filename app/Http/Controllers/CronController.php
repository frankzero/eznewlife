<?php

namespace App\Http\Controllers;

use App\Article;
use Carbon\Carbon;
use DB;
use Image;

use Illuminate\Support\Facades\File;
class CronController extends Controller
{

    public function publish()
    {
        DB::table('articles')->where('status', 2)->where('publish_at', '<', Carbon::now())->where('deleted_at', null)->update(['status' => 1]);
        return response()->json(['result' => '1', 'desc' => '更新未發佈資料']);
    }

    public function thumbnail()
    {
        $articles = Article::all();
        foreach ($articles as $k => $article) {
            $destinationPath = public_path() . '/focus_photos/';
            $smallPath = public_path() . '/focus_photos/200/';
            $one_Path = public_path() . '/focus_photos/100/';
            $four_Path = public_path() . '/focus_photos/400/';
            $thumbnail = ['200' => $smallPath, '100' => $one_Path, '400' => $four_Path];
            if (File::exists($destinationPath . $article->photo) and !empty($article->photo)) {
                //

                $file = $article->photo;
                foreach ($thumbnail as $key => $val) {
                    echo $destinationPath . $article->photo . "<br>";
                    Image::make($destinationPath . $article->photo)->resize($key, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($val . $article->photo);
                }
                // Storage::disk('local')->put($article->id.'.'.$extension,  File::get($file));
                // $article->photo = $fileName;
                // $article->save();
            }
        }
    }
}
