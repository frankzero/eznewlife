<?php 
    

    $og_url = Request::url();


    if(Route::currentRouteName()=='articles.show'){
        $og_type = 'article';
    }else{
        $og_type = 'website';
    }




    $og_title = htmlentities($article->title);



    $og_description = $article->summary;




    $og_image = call_user_func(function () use($article) {

        if(empty( $article->photo )) return cdn("/images/index.png");

        $og_path = '/focus_photos/' .$article->photo;
        $og_file = public_path() . $og_path;
        
        if(!file_exists($og_file)) return cdn("/images/index.png");

        $og_time = filemtime($og_file);

        if($og_time < (time() - 600) ){
            return cdn($og_path).'?tt='.date('Ymd_His', $og_time);
        }

        return cdn($og_path).'?tt='.date('Ymd_His');

        

    });


?>



<meta property="og:url" content="<?=$og_url;?>" />
<meta property="og:type"               content="<?=$og_type;?>" />
<meta property="og:title"              content="<?=$og_title;?>" />
<meta property="og:description"        content="<?=$og_description;?>" />
<meta property="og:image"              content="<?=$og_image;?>" />
<meta property="og:locale"             content="zh_TW" />
<meta property="fb:app_id"             content="<?= ff\config('app_id');?>"/>
