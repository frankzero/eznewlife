<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;use App\Article,App\Category,Cache;
use Carbon\Carbon;
class PublishArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'articles:publsih description';

    /**
     * Create a new command instance.
     *$sql = "update `articles` set `status` = '1' where `status` = '2' and `publish_at` < '$now' and `deleted_at` is null";
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
        //
        $start=time();
        $this->info('category start...');
        DB::table('articles')->where('status', 2)->where('publish_at', '<', Carbon::tomorrow())->where('deleted_at', null)->update(['status' => 1]);
        $all_categories=Category::lists('id');
        foreach ($all_categories as $k=>$id){
            save_cate_cache($id);
        }
        $this->info('enl tag start...');
        save_tag_cache(null,'enl');
        $this->info('dark tag start...');
        save_tag_cache(null,'dark');
        $end=time();

        $path = "/home/eznewlife/ad.eznewlife.com/laravel/enl_cache";
        exec("chmod -R 777 $path");
        // chmod_r($path,0777); //兩種方法都可以
        $end = time();
        $this->comment("all ==" . number_format($end - $start) . "ms");

    }
}
