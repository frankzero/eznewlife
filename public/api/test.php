<?php 
require __DIR__.'/autoload.php';
error_reporting(E_ALL);


$url = 'http://share.youthwant.com.tw/DE2061667.html';
$url = 'http://www.animen.com.tw/NewsArea/NewsItemDetail?NewsId=17775&categoryId=1&realCategoryId=1&subCategoryId=3';
$url = 'https://www.top1health.com/Article/302/40386';


$req = new __request($url);
$req->clearcookie();

//$req->setHeader('Referer', 'http://iguang.tw/t/goto?url=http://share.youthwant.com.tw/DE2065298.html');
//$req->setHeader('Host', 'www.animen.com.tw');
//$req->setHeader('Accept-Encoding', 'gzip, deflate, sdch');
$req->setHeader('Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8');
$req->setHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.82 Safari/537.36');

/*
Accept-Encoding:gzip, deflate, sdch
Accept-Language:zh-TW,zh;q=0.8,en-US;q=0.6,en;q=0.4,zh-CN;q=0.2,es;q=0.2,ja;q=0.2,ru;q=0.2,fr;q=0.2,it;q=0.2,gl;q=0.2
Cache-Control:no-cache
Connection:keep-alive
Cookie:__asc=ad5deb7c1566837f6db7ab52f92; __auc=6979ee60156681020344b634b2e; _ga=GA1.3.1542508498.1470624703
Host:www.animen.com.tw
Pragma:no-cache
Upgrade-Insecure-Requests:1
User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.82 Safari/537.36
*/

$r = $req->response();

var_dump($r);
exit;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, '1');
$r = curl_exec($ch);
curl_close($ch);
echo $r;
exit;
//$r = file_get_contents($url);
$req = new __request($url);
$req->clearCookie();

$req->setHeader('Host', 'ck101.com');
$req->setHeader('Accept-Encoding', 'gzip, deflate, sdch');



$r = $req->response();

echo $r;
exit;

require __DIR__.'/autoload.php';
error_reporting(E_ALL);


$url='http://www.up01.cc/post/20671';
$forbid_url = 'http://www.up01.cc/forbid.html';

$req = new __request($url);
//$req->clearCookie();

$r = $req->response();

//print_r($req->http_response_header);

//print_r($req->cookie);

//echo $r;

$req2 = new __request($forbid_url);

$req2->cookie = $req->cookie;

//print_r($req2->cookie);

$r2 = $req2->response();
//echo $r2;

$req3 = new __request($url);
$req3->cookie = $req2->cookie;
$req3->setHeader('Referer', $forbid_url);
$req3->setCookie('over18', '1');

$req3->response();


$u = new __url($url);

$u->set('agree', '1');

$req4 = new __request($u->toString());
$req4->cookie = $req3->cookie;
$req4->setHeader('Referer', $url);

$response = $req4->response();

echo $response;
exit;





//echo $r;

//echo file_get_contents($url);


exit;
$url='http://tw.anyelse.com/article/206080.html';

//$r = file_get_contents($url);

//echo $r;

$req = new __req($url);
echo $req->response();

exit;
$html = file_get_contents(__DIR__.'/test_loading.php');

$prefix='<article>';
$tail='<\\/article>';
preg_match('/'.$prefix.'([\s\S.]*)'.$tail.'/iU',$html, $match);


$html =$match[1];
$prefix='<td';
$tail='<\\/td>';
preg_match('/'.$prefix.'([\s\S.]*)'.$tail.'/iU',$html, $match);


$match[0];







$query = file_get_html($html);

$el = $query->find('article td')[0];

echo $el->outertext;


exit;


$url = 'http://www256.eyny.com/thread-10828695-1-3DN3CFFH.html';

$url = http::get('url');

if(!$url) exit;
// $url = 'http://www256.eyny.com/thread-10863517-1-20UPEH29.html';

$eyny = new get_html_eyny_com($url);
//$eyny->debug=true;

//echo $eyny->test();

echo $eyny->response();

exit;

$req = new __request($url, 'GET');
$req->response();

print_r($req->http_response_header);


$r = file_get_contents($req->cookiefile);
print_r( json_decode($r) );



