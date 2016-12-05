<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;use App\Article,App\Category as Category,Cache;
use Carbon\Carbon;;
class RefreshCaches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'caches:refresh {type=all} {limit?} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all caches';

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
    {   $loop=500;
        $type=$this->argument('type');
        $limit=$this->argument('limit');
        $this->info($type);
        $this->info($limit);
        $start=time();
        if ($type=="para" or $type=="all") {
            $this->info('parameter start...');
            save_para_cache();
        }
        if ($type=="cate" or $type=="all") {
            $this->info('category start...');
            $all_categories = Category::lists('id');
            foreach ($all_categories as $k => $id) {
                save_cate_cache($id);
            }
        }
        if ($type=="tag" or $type=="all") {
            $this->info('enl tag start...');
            save_tag_cache(null, 'enl');
            $this->info('dark tag start...');
            save_tag_cache(null, 'dark');
            $this->info('god tag start...');
            save_tag_cache(null, 'god');
        }
        if ($type=="article" or $type=="all") {

            if (!empty($limit)) {
                $all_articles = Article::publish()->take($loop)->skip($limit * $loop)->lists('id');
            } else {
                $all_articles = Article::publish()->lists('id');
            }
            foreach ($all_articles as $k => $id) {
                $this->info("articles start ..." . $id);
                save_article_cache($id);
            }
        }
        $end=time();

        $this->comment("cache== [".$type.']'.($end-$start)."ms");
        $this->info("chmod...");
        $path="/home/eznewlife/ad.eznewlife.com/laravel/enl_cache";
        exec("chmod -R 777 $path");
       // chmod_r($path,0777);
        $end=time();

        $this->comment("all ==".number_format($end-$start) ."ms");
        /*
          if( chmod($path, 0777) ) {
              // more code
              chmod($path, 0755);
          }*/
    }

}
