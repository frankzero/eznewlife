<?php 

require __DIR__.'/autoload.php';

error_reporting(E_ALL);

index_handle();





function index_handle(){

    $route = new __router;




    $route->get('/api/lastest', function(){
        $paged = '1';

        if(http::get('paged')){
            $paged = http::get('paged');
        }

        if( !is_numeric($paged) ) $paged = '1';

        $from = ($paged-1)*12;
        $limit = "LIMIT $from, 12";

        $sql = "SELECT id, title, photo FROM `articles` WHERE `category_id` = 9 ORDER BY `articles`.`id` DESC $limit";
        

        $conn = oconn('S');

        $rows = $conn->get($sql);

        
        $data=[];
        for($i=0, $imax=count($rows); $i<$imax; $i++){
            $row = $rows[$i];
            $tmp = getArticlePhoto($row->id, $row->title, $row->photo);

            $data[] = $tmp;
        }

        response($data);

    });




    $route->get('/api/top', function(){
        $paged = '1';

        if(http::get('paged')){
            $paged = http::get('paged');
        }


        if( !is_numeric($paged) ) $paged = '1';


        $from = ($paged-1)*12;
        $limit = "LIMIT $from, 12";

        $sql = "SELECT id, title, photo FROM `articles` WHERE `category_id` = 9 ORDER BY `articles`.`score` DESC $limit";
        

        $conn = oconn('S');

        $rows = $conn->get($sql);

        
        $data=[];
        for($i=0, $imax=count($rows); $i<$imax; $i++){
            $row = $rows[$i];

            $tmp = getArticlePhoto($row->id, $row->title, $row->photo);

            $data[] = $tmp;
        }

        response($data);

    });


    $route->get('/api/article/{int}', function($unique_id){
        $conn = oconn('S');

        if( !is_numeric($unique_id) ){
            response([]);
            return;
        }



        $sql="select articles_id from articles_map where unique_id=:unique_id";
        $row = $conn->getOne($sql, $unique_id);
        if($row===false){
            response([]);
            return;
        }
        
        $id = $row->articles_id;


        $data = getArticle($id);

        response($data);


    });




    $route->get('/api/search', function(){

        $q=http::get('q');

        if($q === ''){
            response([]);
            return;
        }


        $paged = '1';

        if(http::get('paged')){
            $paged = http::get('paged');
        }

        if( !is_numeric($paged) ) $paged = '1';



        $from = ($paged-1)*12;
        $limit = "LIMIT $from, 12";

        $sql = "SELECT id, title, photo FROM `articles` WHERE title LIKE :q and `category_id` = 9 ORDER BY `articles`.`id` DESC $limit";
        

        $conn = oconn('S');

        $rows = $conn->get($sql, '%'.$q.'%');

        
        $data=[];
        for($i=0, $imax=count($rows); $i<$imax; $i++){
            $row = $rows[$i];

            $tmp = getArticlePhoto($row->id, $row->title, $row->photo);

            $data[] = $tmp;
        }

        response($data);


    });


// SELECT * FROM `articles` WHERE `category_id` = 9 ORDER BY `articles`.`id` DESC

// SELECT * FROM `articles` WHERE `category_id` = 9 ORDER BY `articles`.`score` DESC


}



function getArticle($id){

    $conn = oconn('S');

    $sql="select content from articles where id=:id";
    $row = $conn->getOne($sql, $id);

    $content = $row->content;


    $data=[];
    $query = file_get_html($content);
    $imgs = $query->find('img');
    for ($i=0,$imax=count($imgs); $i < $imax; $i++) { 
        $img=$imgs[$i];
        $src = $img->getAttribute('src');

        if( strpos($src, 'http') ===false){
            $src = 'http://avbody.info/'.ltrim($src, '/');
        }

        $data[]=$src;
    }


    return $data;

}




function getArticlePhoto($id, $title, $photo){

    $conn = oconn('S');

    $sql="select unique_id from articles_map where articles_id=".$id;
    $d = $conn->getOne($sql);

    $tmp = new stdClass;
    $tmp->id = $d->unique_id;
    $tmp->title = $title;

    $_photo = 'nophoto.png';

    if($photo) $_photo = $photo;
    
    $tmp->photo = 'http://avbody.info/focus_photos/'.$_photo;

    return $tmp;
}


function response($data){

    if(http::get('debug')){
        echo "<pre>";
        print_r($data);
    }

    echo '/*****/';
    echo json_encode($data, JSON_PRETTY_PRINT);
    echo '/*****/';
}