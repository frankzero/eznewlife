<?php 

exit;
require __DIR__.'/../frank/autoload.php';
correct();
exit;


function correct(){
    $conn=ff\conn();

    $sql="select * from articles";

    $stmt = $conn->select($sql);

    $conn2 = ff\make_conn();

    while($row = $stmt->fetch(PDO::FETCH_OBJ)){
        $category_id = $row->category_id;
        $n = $category_id-0;

        if($n<=5) continue;

        $to_id = get_real_cat_id($category_id);
        echo $row->id.' '.$category_id.' '.$to_id."\n";
        //$sql="UPDATE articles SET category_id='".$to_id."' WHERE id=".$row->id;
        //$conn2->update($sql);
    }

}


function get_real_cat_id($category_id){
    static $dic=null;

    if($dic===null){
        $conn=ff\conn();
        $sql="select * from _category_map";
        $rows = $conn->get([$sql], 'FETCH_OBJ');
        $dic=[];
        for ($i=0,$imax=count($rows); $i < $imax; $i++) { 
            $row=$rows[$i];
            $dic[$row->from_id] = $row->to_id;
        }
    }

    $to_id=1;
    if(isset($dic[$category_id])) $to_id=$dic[$category_id];

    return $to_id;

}
    



function id(){
    static $c=0;
    $c++;
    return 'id'.$c;
}

function cmap_id($id){
    static $dic=null;
    
    if($dic===null){
        $conn=ff\conn();
        $sql="select * from _category_map";
        $rows = $conn->get([$sql], 'FETCH_OBJ');
        for ($i=0,$imax=count($rows); $i < $imax; $i++) { 
            $row=$rows[$i];
            $dic[$row->from_id] = $row->to_id;
        }

    }

    if(isset($dic[$id])){
        return $dic[$id];
    }

    return 1;
}



if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $from_id = $_POST['from_id'];
    $to_id = $_POST['to_id'];
    
    if($from_id &&$to_id){
        $conn=ff\conn();
        $conn->debug(true);
        $sql="select * from _category_map WHERE from_id=:from_id";
        $row = $conn->getOne($sql, $from_id);

        if($row===false){
            $sql="INSERT INTO _category_map SET from_id=:from_id, to_id=:to_id";
            $conn->insert($sql, $from_id, $to_id);
            exit;
        }

        $sql="UPDATE _category_map SET to_id=:to_id WHERE from_id=:from_id";
        $conn->update($sql, $to_id, $from_id);
        exit;
    }
    
    exit;
}

$conn=ff\conn();
$sql="select id, name, description from categories";
$rows = $conn->get([$sql], 'FETCH_OBJ');

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title></title>

<style>
body{
    font-size:1em;
}

.cat_name{
    width:200px;
    display:inline-block;
}

.p{
    border-bottom:1px solid #000;
    padding:2px;
}

.active{
    background-color:#00B0ED;
}
</style>
</head>
<body>

<form >
    <?php for($i=5,$imax=count($rows); $i<$imax; $i++):?>
        <?php $row=$rows[$i];?>
        <?php $id=cmap_id($row->id);?>
        <div class="p">
            <div class="cat_name"> <?=$row->name;?> </div>
            <label ><input name="cat_<?=$row->id?>" type="radio" data-id="<?=$row->id;?>" value="1" <?=($id==1?'checked':'');?> >達人殿堂 </label>
            <label ><input name="cat_<?=$row->id?>" type="radio" data-id="<?=$row->id;?>" value="2" <?=($id==2?'checked':'');?> >酷索一下 </label>
            <label ><input name="cat_<?=$row->id?>" type="radio" data-id="<?=$row->id;?>" value="3" <?=($id==3?'checked':'');?> >新奇古怪 </label>
            <label ><input name="cat_<?=$row->id?>" type="radio" data-id="<?=$row->id;?>" value="4" <?=($id==4?'checked':'');?> >兩性與生活 </label>
            <label ><input name="cat_<?=$row->id?>" type="radio" data-id="<?=$row->id;?>" value="5" <?=($id==5?'checked':'');?> >APP市集 </label>
        </div>
    <?php endfor;?>
    
</form>

<script src="/js/EZ.2.js"></script>

<script>
ff('input[type="radio"]').click(function(e){
    var from_id = ff(this).attr('data-id');
    var to_id = ff(this).val();

    console.log(from_id, to_id);
    var p = {};
    p.from_id=from_id;
    p.to_id = to_id;

    xhr('cc.php', 'POST', p);
});

ff('.p').click(function(e){
    ff(this).addClass('active');
});
</script>
</body>
</html>