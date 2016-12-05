<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;use App\Article,App\Category as Category,Cache;
use Carbon\Carbon;

class CheckTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tags:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check tags';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   


        $conn = oconn('M');
        $conn2 = omake_conn('M');

        $sql="select taggable_id,tag_name from tagging_tagged;";
        $stmt = $conn->select($sql);

        $c=0;

        while($row = $stmt->fetch(\PDO::FETCH_NUM)){
            //echo $row[0]."\n";
            $id = $row[0];
            $slug = $row[1];

            $sql = "select category_id from articles where id=$id";
            $r = $conn2->getOne($sql);
            $category_id = $r->category_id - 0;


            if($category_id <=6){
                $prop = 'enl_tag_ids_'.$slug;
            }else if($category_id <= 8){
                $prop = 'dark_tag_ids_'.$slug;
            }


            if(!cache_get($prop)){
                $c++;
                echo $prop."\n";
            }
        }
    }
}
