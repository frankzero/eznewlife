<?php 

require __DIR__.'/../frank/autoload.php';


if(isset($_GET['max_article_id'])){
    echo a_max_id();
}

if(isset($_GET['test'])){
    $r=[];

    //$r['s33'] = file_get_contents('http://ad33.eznewlife.com/test_slave.php?max_article_id=1');
    $r['s34'] = file_get_contents('http://ad34.eznewlife.com/test_slave.php?max_article_id=1');
    // $r['s35'] = file_get_contents('http://ad35.eznewlife.com/test_slave.php?max_article_id=1');
    $r['s36'] = file_get_contents('http://ad36.eznewlife.com/test_slave.php?max_article_id=1');
    $r['s37'] = file_get_contents('http://ad37.eznewlife.com/test_slave.php?max_article_id=1');
    //$r['s38'] = file_get_contents('http://ad38.eznewlife.com/test_slave.php?max_article_id=1');
    $r['s41'] = file_get_contents('http://ad41.eznewlife.com/test_slave.php?max_article_id=1');
    //$r['s42'] = file_get_contents('http://ad42.eznewlife.com/test_slave.php?max_article_id=1');
    $r['s43'] = file_get_contents('http://s43.avbody.info/test_slave.php?max_article_id=1');
    $r['s45'] = file_get_contents('http://s45.avbody.info/test_slave.php?max_article_id=1');
    $r['s46'] = file_get_contents('http://ad46.eznewlife.com/test_slave.php?max_article_id=1');
    $r['s47'] = file_get_contents('http://ad47.eznewlife.com/test_slave.php?max_article_id=1');
    $r['s48'] = file_get_contents('http://ad48.eznewlife.com/test_slave.php?max_article_id=1');

    echo "<pre>";
    print_r($r);
}

 


function a_max_id(){
    $conn = oconn('S');
    $sql="select max(id) as max from articles";
    $row = $conn->getOne($sql);

    return $row->max;

}