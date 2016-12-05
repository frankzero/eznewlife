<?php

use Illuminate\Database\Seeder;
use App\Category;
class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('categories')->truncate();
        $categories=['達人殿堂','酷索一下','新奇古怪','兩性與生活','APP市集'];
        $categories_desc=['各路達人齊聚一堂!','Kuso搞笑大集合!','世界上無奇不有~ 你都見過嗎?!','觀看全部分類為「兩性與生活」的文章','ENL 自主開發的APP'];
        foreach($categories as $key =>$val) {
            Category::create([
                'name' => $val,
                'description'=>$categories_desc[$key]
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        //
    }
}
