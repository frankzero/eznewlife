<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category,App\Article,Cache;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;
class PrivacyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view("privacies.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function avbody()
    {
        $page = ['title' => 'AVBODY 隱私權政策', 'sub_title' => '',
            'url' => '', 'share_url' => '', 'photo' => ''];

        $agent = new Agent();
        return view("privacies.avbody")->with('plan', 1)  ->withPage($page)   ->with('mobile', $agent->isMobile());
    }
    public function get_enl_category()
    {
        /*** 取得類別資料**/
        $tmp_cache='enl_categories';
        if (cache_has($tmp_cache)) {
            $categories  = cache_get($tmp_cache);
        } else {
            $categories= Category::getList();
            cache_forever($tmp_cache,  $categories);
        }
        return $categories;
    }
    protected function get_enl_rand_data($count){
        /**
         * 亂數
         */
        $rand=cache_get('enl_ids');
        $rand_article_ids = array_rand($rand,$count*2);
        $i=0;
        $rand_articles=[];
        foreach ($rand_article_ids as $k=>$rand_key){
            $rand_id=$rand[$rand_key];
            if (cache_has('article_' . $rand_id)) {
                $rand_articles[$i]= cache_get('article_' . $rand_id);
                $i++;
            }
            if ($i>=$count) break;
        }
        return $rand_articles;
    }
    public function enl()
    {
        $expiresAt = Carbon::now()->addMinutes(5);
        $categories =$this->get_enl_category();
        $page = ['title' => 'ENL 隱私權政策', 'sub_title' => '',
            'url' => '', 'share_url' => '', 'photo' => ''];

        $rand_articles=$this->get_enl_rand_data(10);
        $agent = new Agent();
        return view("privacies.enl")->with('plan', 1)
            ->withPage($page)->with('categories', $categories)
            ->with('rand_articles', $rand_articles)
        ->with('mobile', $agent->isMobile());
    }


}
