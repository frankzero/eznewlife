<?php 

require __DIR__.'/autoload.php';

error_reporting(E_ALL);

index_handle();





function index_handle(){

    $id = http::get('id');

    $conn = oconn('M');

    
    


    if($id){

        handle_show($id);
        return;
    }



    require __APP__.'tpl/index.html';

}



function handle_show($id){
    $conn = oconn('S');

    $sql="select * from ezgo where unique_id=:unique_id";
    $row = $conn->getOne($sql, $id);

    if($row===false){
        header("Location: ".http::domain().'/'.client_path(__APP__));
        return;
    }

    $url = $row->url;
    $unique_id=$row->unique_id;

    $response = safebrowsing($url);

    $safe = true;
    if($response=='malware'){
        $safe = false;
    }


    $content = file_get_contents($url);
    // 抓 title
    preg_match('/<title>(.*)<\/title>/iU',$content,$match);
    $title = $match[1];
    
    // description
    $description='';
    preg_match('/<meta[^>]+property="og:description"[^>]+content=[\'"]([^>"\']*)[\'"][^>]+>/iU',$content,$match);
    
    if(!empty($match[1]))$description = $match[1];
    //抓meta  name="description" , name="keywords" property="og:image , og:title ,og:description, og:type
    preg_match_all('/<meta\s[^>]*>/iU',$content,$match);
    
    
    $meta = '';
    $ms = $match[0];
    $filter = array();
    $filter[]='name="description"';
    $filter[]='name=\'description\'';
    $filter[]='name="keywords"';
    $filter[]='name=\'keywords\'';
    $filter[]='property="og:image"';
    $filter[]='property=\'og:image\'';
    //$filter[]='property="og:url"';
    //$filter[]='property=\'og:url\'';
    $filter[]='property="og:title"';
    $filter[]='property=\'og:title\'';
    $filter[]='property="og:description"';
    $filter[]='property=\'og:description\'';
    $filter[]='property="og:type"';
    $filter[]='property=\'og:type\'';
    
    for($i=0,$imax=count($ms);$i<$imax;$i++){
        $m = $ms[$i];
        for($j=0,$jmax=count($filter);$j<$jmax;$j++){
            $f = $filter[$j];
            if(strpos($m,$f)!==false){
                $meta.=$m."\n";
                break;
            }
        }
    }
    
    //echo $meta;

    require __APP__.'tpl/show.html';

}