<?php

namespace App\Listeners;
use App\AvUser;
use App\Events\AvUserChecked;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Session\Store;

class SaveAvUserListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $session;
    public $av_user;
    public function __construct(AvUser $av_user)
    {
        $this->av_user = $av_user;
        //dd($av_user);
    }


    /**
     * Handle the event.
     *
     * @param  BlogView  $event
     * @return void
     */

    public function handle(AvUserChecked $av_user)
    {
       var_dump([\Request::route()->getName(),$av_user]);

        //先进行判断是否已经查看过
        //if (!$this->hasViewedBlog($post)) {
            //保存到数据库
        //$av_user->login_counts =$av_user->login_counts + 1;
       // $av_user->save();
            //看过之后将保存到 Session
          //  $this->storeViewedBlog($post);
        //}
    }

    protected function hasViewedBlog($post)
    {
        return array_key_exists($post->id, $this->getViewedBlogs());
    }

    protected function getViewedBlogs()
    {
        return $this->session->get('viewed_Blogs', []);
    }

    protected function storeViewedBlog($post)
    {
        $key = 'viewed_Blogs.'.$post->id;

        $this->session->put($key, time());
    }

    /**
     * Handle the event.
     *
     * @param  AvUserChecked  $event
     * @return void
     */

}
