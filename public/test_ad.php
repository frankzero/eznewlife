
<?php 
$asids=[];
$asids[]='912441';
$asids[]='863778';
$asids[]='861550';
$asids[]='912443';
$asids[]='915827';


?>


<?php for ($i=0,$imax=count($asids); $i < $imax; $i++):?>
    <?php $asid=$asids[$i];?>
<h1><?=$asid;?></h1>

<div>
<!-- i-mobile for SmartPhone client script -->
 <script type="text/javascript">
  imobile_tag_ver = "0.3"; 
  imobile_pid = "50122"; 
  imobile_asid = "<?=$asid;?>"; 
  imobile_type = "inline";
 </script>
 <script type="text/javascript" src="http://spad.i-mobile.co.jp/script/adssp.js?20110215"></script>

 <textarea>
<!-- i-mobile for SmartPhone client script -->
 <script type="text/javascript">
  imobile_tag_ver = "0.3"; 
  imobile_pid = "50122"; 
  imobile_asid = "<?=$asid;?>"; 
  imobile_type = "inline";
 </script>
 <script type="text/javascript" src="http://spad.i-mobile.co.jp/script/adssp.js?20110215"></script>
 </textarea>
 </div>
    


<?php endfor;?>
