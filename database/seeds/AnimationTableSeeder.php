<?php

use Illuminate\Database\Seeder;
use App\Animation;
class AnimationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
     //  Animation::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        //$animations=Animation::orderBy('id','asc')->get();

        /*
        for ($i=1;$i<52;$i++){
            // echo $animation->id;
            $jpg=[44,29,30,31,32,33,34,35,20,27,22];
            if (in_array($i,$jpg)) {
                $photo = $i . ".jpg";
            } else {
                $photo =$i.".gif";
            }
            Animation::create(array(

                'id' =>$i,
                'photo'=>$photo,
                'title'=>'動圖'.$i,
                'created_user'=>1,
                'updated_user'=>1,



            ));
        }*/
/*
        $animations=Animation::where('org_photo','like','%mp4')->orderBy('id','asc')->get();

        foreach ($animations as $key=>$animation){
            $animation->org_photo=$animation->id.".gif";

            $animation->save();
        }exit;*/
        File::makeDirectory(public_path() . '/animation_photos/org', 0777, true, true);
        File::makeDirectory(public_path() . '/animation_photos/preview', 0777, true, true);
       // $animations=Animation::where('feedback','=','0')->orderBy('id','asc')->get();
        $show_dir = public_path() . '/animation_photos/';
        $org_dir = public_path() . '/animation_photos/org/';
        $preview_dir = public_path() . '/animation_photos/preview/';
        //先備分一分org
        /*
        foreach ($animations as $key=>$animation){
            $org_photo = $org_dir . $animation->photo;
            $show_photo=$show_dir.$animation->photo;
            if ( !empty($animation->photo) and file_exists($show_photo)) {
                File::copy($show_photo, $org_photo);
            }
            echo  $animation->id." copy OK\n";
        }*/
        //開始製圖
        echo  "use=".round(memory_get_usage()/1024)."\n";
        echo  "use_peak=".round(memory_get_peak_usage()/1024)."\n";
        for ($i=404;$i<492;$i++){
            $animation=Animation::find($i);
        //foreach ($animations as $key=>$animation){
            ///echo  $animation->id." start\n";
            $org_photo=$show_dir.$animation->id.".gif";
			echo  $org_photo." \n";
          // $result=animation($animation->id,$org_photo);
           // echo  $org_photo." \n";
            //dd($result);
            if (file_exists($org_photo)) {
                list($width, $height, $type, $attr) = getimagesize($org_photo);
                $animation->feedback = 0;
                $animation->org_photo = basename($org_photo);
                $animation->photo_size = filesize($org_photo);
                $animation->photo_width = $width;
                $animation->photo_height = $height;
                $animation->photo =basename($org_photo);

                $animation->save();
                echo $animation->id . " cover OK\n";
                echo "use=" . round(memory_get_usage() / 1024) . "\n";
            }


        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
