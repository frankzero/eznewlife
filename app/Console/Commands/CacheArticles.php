<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;use App\Article,App\Category,Cache;
use Carbon\Carbon;
class CacheArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //
        $start=time();
        $this->info('change admin.eznewlife.com to eznewlife.com start...');
        $articles=Article:: where('name', 'like', '%admin.eznewlife.com%')->get();

        foreach ($articles as $k=>$article){
           $this->info("[$k]".$article->id);
            $article->content=str_replace('admin.eznewlife.com','eznewlife.com',$article->content);
            $article->save();
        }

        $end=time();
        $this->info($end-$start);

        /*
        $this->call('email:send', [
            'user' => 1, '--queue' => 'default'
        ]);*/
    }
}
