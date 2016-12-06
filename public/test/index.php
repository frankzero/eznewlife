<?php 
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $url = $_POST['url'];
        $headerstring=$_POST['header'];

        //$url = 'http://demo.eznewlife.com/test/test.php';

        $options = array(
          'http'=>array(
            'method'=>'GET',
            'header'=>$headerstring
          )
        );

        $context = stream_context_create($options);
        
        $content = file_get_contents($url, false, $context);

        echo "<pre>";
        print_r($http_response_header);

        //$content= mb_convert_decoding($content, "UTF-8");
        header("Content-Type:text/html; charset=utf-8");
        echo gzdecode($content);
        exit;
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title></title>
<style>
input[name="url"]{
    padding:5px;
    width:1000px;
}
</style>
</head>
<body>

<form action="" method="POST">

<p><input type="text" name="url" value="http://eznewlife.com:80/146539/攝影師老婆拍下了老公的「憂鬱症日常」卻沒想到自己胸前「粉紅色的亮點」意外被鏡子出賣啦！"></p>

<p><textarea name="header" id="header" cols="100" rows="30">
Referer: http://m.facebook.com
User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 10_0_1 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) Mobile/14A403 [FBAN/FBIOS;FBAV/63.0.0.37.140;FBBV/37606630;FBRV/38815424;FBDV/iPhone7,2;FBMD/iPhone;FBSN/iPhone OS;FBSV/10.0.1;FBSS/2;FBCR/遠傳電信;FBID/phone;FBLC/zh_TW;FBOP/5]
X-Purpose: preview
Accept-Encoding: gzip, deflate
Host: eznewlife.com
X-FB-HTTP-Engine: Liger
Connection: keep-alive
</textarea></p>
<button>submit</button>
</form>



<script>

function xhrToSend(){
    // Attempt to creat the XHR2 object
    var xhr;
    try{
        xhr = new XMLHttpRequest();
    }catch (e){
        try{
            xhr = new XDomainRequest();
        } catch (e){
            try{
                xhr = new ActiveXObject('Msxml2.XMLHTTP');
            }catch (e){
                try{
                    xhr = new ActiveXObject('Microsoft.XMLHTTP');
                }catch (e){
                    statusField('\nYour browser is not' + 
                        ' compatible with XHR2');                           
                }
            }
        }
    }
    xhr.open('GET', 'http://eznewlife.com:80/146539/攝影師老婆拍下了老公的「憂鬱症日常」卻沒想到自己胸前「粉紅色的亮點」意外被鏡子出賣啦！', true);
    
    var header = document.getElementById('header').value;

    header = header.split("\n");

    for (i=0,imax=header.length; i < imax; i++) { 
        var h =header[i];
        h = h.split(':');
        xhr.setRequestHeader(h[0], h[1]);
    }

    xhr.onreadystatechange = function (e) {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.response);
        }
    };
    xhr.send(null);
    numberOfBLObsSent++;
}; 

</script>

</body>
</html>