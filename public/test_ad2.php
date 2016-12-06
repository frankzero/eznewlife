<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title></title>
<style>
.ad_block{
    border:2px solid red;
    margin-top:100px;
}

a{
    font-size:5em;
}

body{
    height:2500px;
}

</style>
</head>
<body>

<a href="/test_ad2.php"> 點我重整 </a>


<div class="ad_block">
    <div id="ENL300x250" style="padding: 0px"></div>
    <script type="text/javascript" src="http://soma-assets.smaato.net/js/smaatoAdTag.js"></script>
    <script>

    function callBackForSmaato(status){
    if(status == "SUCCESS"){
    console.log('callBack is being called with status : ' + status);
    } else if (status == "ERROR"){
    console.log('callBack is being called with status : ' + status);
    }
    }; 
    SomaJS.loadAd({
    adDivId : "ENL300x250",
    publisherId: 1100021646,
    adSpaceId: 130126876,
    dimension: "medrect",
    dimensionstrict: true,
    keywords: 
    "taiwan,viral,male,men,social,entertainment,life,celebrity,tabloid,gossip"
    },callBackForSmaato);
    </script>
</div>


<div class="ad_block">
    <script language="javascript">
    pad_width=300;
    pad_height=250;
    pad_customerId="PFBC20160802002";
    pad_positionId="PFBP201608030001C";
    </script>
    <script id="pcadscript" language="javascript" src="https://kdpic.pchome.com.tw/img/js/xpcadshow.js"></script>

</div>



<div class="ad_block">
<SCRIPT SRC="http://ib.adnxs.com/ttj?id=9306105&cb=[CACHEBUSTER]&pubclick=[INSERT_CLICK_TAG]" TYPE="text/javascript"></SCRIPT>
<!-- END TAG -->
</div>




</body>
</html>