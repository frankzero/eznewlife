<?php 

namespace ff;

function conn($dbtype='S'){
    static $dic=[];

    if(isset($dic[$dbtype])) return $dic[$dbtype];


    $conn = make_conn($dbtype);
    
    $dic[$dbtype]=$conn;

    return $conn;
    
}

function make_conn($dbtype){
    $db_user=_env($dbtype.'_DB_USERNAME');
    $db_host=_env($dbtype.'_DB_HOST');
    $db_password=_env($dbtype.'_DB_PASSWORD');
    $db_name=_env($dbtype.'_DB_DATABASE');

    $dsn = "mysql:host=$db_host;dbname=$db_name";
    $conn = new \__mypdo($dsn, $db_user, $db_password);
    return $conn;
}

function _env($key){
    static $dic = null;
    
    if($dic !== null) return $dic[$key];

    $dic=[];

    $rs = file_get_contents(__DIR__.'/../.env');
    $rs = explode("\n", $rs);
    
    for ($i=0,$imax=count($rs); $i < $imax; $i++) { 
        $r=$rs[$i];

        if(strpos($r, '=') !== false){
            $r = explode('=', $r);
            $k = $r[0];
            $v = $r[1];
            $dic[$k]=$v;
        }
    }

    return $dic[$key];
}


function cookie($key, $value=null, $time=null, $path='/' ){
    if( $value === null ){
        if(!isset($_COOKIE[$key]) ){
            return '';
        }
        return $_COOKIE[$key];
    }else{
        if($time === null) $time = 360000;
        $time = time()+$time;
        setcookie($key, $value, $time, $path);
    }

}

function delete_cookie($key){
    
    unset($_COOKIE[$key]);

    setcookie($key,null,-1,'/');

}



function ez_print_r($content){
    echo "<pre>";
    print_r($content);
    echo "</pre>";
}


/*
    當公用變數使用 
    g( 'var1', '1');
    echo g('var1');
*/

function g( $key, $value=null){
    static $data = array();

    if($key==='showmeall'){
        return $data;
    }

    if($value === null){
        return ( isset($data[$key])) ? $data[$key] : '';
    }else{
        $data[$key] = $value;
    }
}




function ez_do_hash($password,$key=''){
    return hash('sha512', $password.$key); // hash the password with the unique salt.
}

/*
    簡單加密
*/
function ez_encrypt($text){
    $salt = '0OfW6e1eUeymty8l2X3V';
    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

/*
    簡單解密
*/
function ez_decrypt($text){
    $salt = '0OfW6e1eUeymty8l2X3V';
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}


/*
    正規 擷取 中間字串 
*/
function ez_cut_string( $response, $prefix, $tail , $fullstring = false){
    
    preg_match('/'.$prefix.'([\s\S.]*)'.$tail.'/iU',$response,$match);
    
    if($fullstring) return $match[0];
    else return $match[1];
}



/*
    移除 html tag
*/
function ez_removetag( $html, $tagname){
    
    $html = preg_replace('#<'.$tagname.'([^>]*)>(.*?)</'.$tagname.'>#is', '', $html);
    
    return $html;
}



/*
    同步資料夾內的檔案 (不包含子資料夾)
*/
function ez_sync_dir($path1,$path2){
    $files = glob($path1.'*.*');
    for($i=0,$imax=count($files);$i<$imax;$i++){
        $f = $files[$i];
        $tmp = explode('/',$f);
        $file = $tmp[count($tmp)-1];
        if(!file_exists($path2.$file)){
            echo $file."\n";
            $r = file_get_contents($f);
            file_put_contents($path2.$file,$r);
        }
    }
}


/*
    判斷字串是不是只有 數字+英文字母
*/
function ez_is_token($token){
    //判斷是 數字+字母
    if (preg_match ("/^[a-z0-9]+$/i", $token)) {
        return true;
    }else{
        return false;
    }
}



function ez_curl_post($url,$post,$referer=''){
   $ch = curl_init();
    $options=array(
        CURLOPT_URL=>$url,
        CURLOPT_POST=>true,
        CURLOPT_RETURNTRANSFER => TRUE, 
        CURLOPT_POSTFIELDS=>$post
    );
    if($referer!=''){
        $options[CURLPOT_REFERER]=$referer;
    }
    curl_setopt_array($ch,$options);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function ez_post($url, $data, $referer='') {
    
    // Convert the data array into URL Parameters like a=b&foo=bar etc.
    $data = http_build_query($data);
    
    // parse the given URL
    $url = parse_url($url);
    
    if ($url['scheme'] != 'http') { 
        die('Error: Only HTTP request are supported !');
    }
    
    // extract host and path:
    $host = $url['host'];
    $path = $url['path'];
    
    
    if( !isset($url['port'])) $port = 80;
    else $port = $url['port'];
    // open a socket connection on port 80 - timeout: 30 sec


    $fp = fsockopen($host, $port, $errno, $errstr, 30);
    
    if ($fp){
        
        // send the request headers:
        fputs($fp, "POST $path HTTP/1.1\r\n");
        fputs($fp, "Host: $host\r\n");
        
        if ($referer != '')
        fputs($fp, "Referer: $referer\r\n");
        
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: ". strlen($data) ."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $data);
        
        $result = ''; 
        while(!feof($fp)) {
            // receive the results of the request
            $result .= fgets($fp, 128);
        }
    }
    else { 
        return array(
        'status' => 'err', 
        'error' => "$errstr ($errno)"
        );
    }
    
    // close the socket connection:
    fclose($fp);
    
    // split the result header from the content
    $result = explode("\r\n\r\n", $result, 2);
    
    $header = isset($result[0]) ? $result[0] : '';
    $content = isset($result[1]) ? $result[1] : '';
    
    // return as structured array:
    return array(
    'status' => 'ok',
    'header' => $header,
    'content' => $content
    );
}


/*
    壓縮 css
*/
function ez_minify_css($buffer){
    // Remove comments
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
     
    // Remove space after colons
    $buffer = str_replace(': ', ':', $buffer);
     
    // Remove whitespace
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
     
    // Enable GZip encoding.
    ob_start("ob_gzhandler");
     
    // Enable caching
    header('Cache-Control: public');
     
    // Expire in one day
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
     
    // Set the correct MIME type, because Apache won't set it for us
    header("Content-type: text/css");
     
    // Write everything out
    echo($buffer);
    $content = ob_get_contents();
    ob_end_clean();
    
    return $content;
}


/*
        壓縮 javascript
    */
function ez_minify_js($code){
    $postdata = array();
    $postdata[]=array('js_code',$code);
    $postdata[]=array('compilation_level','SIMPLE_OPTIMIZATIONS');
    $postdata[]=array('output_format','json');
    $postdata[]=array('output_info','compiled_code');
    $postdata[]=array('output_info','errors');
    $postdata[]=array('output_info','warnings');
    $postdata[]=array('output_info','statistics');
    $postdata[]=array('output_file_name','default.js');
    $code = self::post('closure-compiler.appspot.com','http://closure-compiler.appspot.com/compile',$postdata);
    //echo $content;
    $output = json_decode($code,true);
    $code = $output['compiledCode'];
    return $code;
}


function unique_id($num){
    $t = array(
    'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
    ,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'
    ,'0','1','2','3','4','5','6','7','8','9'
    );
    
    $r = $t[rand(0,51)];
    for($i=1;$i<$num;$i++)
    {
        $r.= $t[rand(0,61)];
    }
    return $r;
}


function getip(){
    static $ip = null;
    
    if($ip === null){
        if(empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = (!empty($_SERVER['REMOTE_ADDR']))?$_SERVER['REMOTE_ADDR']:'';
        }else{
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = $ip[0];
        }
    }
    
    return $ip;
}


function ez_remove_prefix($str, $prefix){
    if (substr($str, 0, strlen($prefix)) == $prefix) {
        $str = substr($str, strlen($prefix));
    }
    return $str;
}

function ez_remove_suffix($str, $suffix){
    if( substr($str, 0-strlen($suffix)) === $suffix){
        $str = substr($str, 0, 0-strlen($suffix));
    }
    return $str;
}

function c($class_name){
    static $dic=[];

    if( isset($dic[$class_name]) ){
        return $dic[$class_name];
    }

    $dic[$class_name] = new $class_name();

    return $dic[$class_name];
}

function current_url(){
    static $current_url = null;
    
    if(null === $current_url){
        $current_url = 'http';
        if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$current_url .= "s";}
        $current_url .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $current_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]; // REQUEST_URI ,PHP_SELF
        } else {
            $current_url .= $_SERVER["SERVER_NAME"];
        }
    }
    
    return $current_url;
}


function current_page_url(){
    static $current_url = null;
    
    if(null === $current_url){
        $current_url = 'http';
        if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$current_url .= "s";}
        $current_url .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $current_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];; // REQUEST_URI ,PHP_SELF
        } else {
            $current_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];;
        }
    }
    
    return $current_url;
}

function mobile_article($id, $article_id, $right_title, $ArticleController,$article){
    
    require __DIR__.'/eznewlife-mobile/functions.php';
    //use ff;


    $c=[];

    $c['root_path'] = __ROOT__;
    $c['site_url'] = current_url().'/';
    $c['paged'] = '1';
    $c['is_home']=0;
    $c['is_single']=1;
    $c['is_category']=0;
    $c['is_archive']=0;
    $c['is_404']=0;
    $c['page_status'] = 'is_single';
    $c['title']='';
    $c['previous_link']='';
    $c['next_link']='';
    $c['max_paged']=$ArticleController->maxpaged('index', 10);
    $c['content']='';
    $c['ad_block_1'] = '';
    $c['ad_block_2'] = '';
    $c['ad_block_3'] = '';
    $c['ad_block_4'] = '';
    $c['ad_block_5'] = '';
    $c['article_id']=$article_id;
    $c['categories']=$article->categories;
    $c['publish_at']=$article->publish_at;
    $c['author']=$article->author->name;
//var_dump($c);
    //$article = $ArticleController->get_article_data($article_id);

    
    if($article===false){
        \abort(404, "文章不存在");
    }

    //$c['og_image'] = current_url().'/focus_photos/'.$article->photo;
    $c['og_image'] = cdn('focus_photos/'.$article->photo);

    $c['content'] = $article->content;
  
    $cp = make_content_paging($c['content'],$article->title);

    $c['content'] = $cp->current_content;
    $c['cp'] = $cp;

  // dd([$c,$article,$cp]);

    $c['title'] = $article->title;
    $c['description'] = $article->summary;
    //$c['current_page_url'] = $ArticleController->current_page_url($article->id);
    $c['current_page_url'] =  $ArticleController->_spell_link($id, $right_title);
       
    //廣告
    $adv = \get_adv($c['content'], $c['page_status']);
    $c['ad_block_1'] = $adv['ad_block_1'];
    $c['ad_block_2'] = $adv['ad_block_2'];
    $c['ad_block_3'] = $adv['ad_block_3'];
    $c['ad_block_4'] = $adv['ad_block_4'];
    $c['ad_block_5'] = $adv['ad_block_5'];
    
    $c['content'] = str_replace('{ad_block_2}',$c['ad_block_2'],$c['content']);
    
    //上下頁連結

    $category_id = $article->category_id; 
    //echo $ArticleController->spell_link($article->id, $article->title);
    
    //■■■■■■■■■■■■■■■■■■■■■■■■■上一頁連結■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
    
    //$id = 

    $c['previous_link'] = isset($article->prev_link)?$article->prev_link:false;
    $c['next_link'] = isset($article->next_link)?$article->next_link:false;
    
    //$c['template_url'] = __DIR__.'/eznewlife-mobile/tpl/';
    $c['tpl'] = __DIR__.'/eznewlife-mobile/tpl/home.html';

   
    $c['is_admin'] = 0;
    //$c['data']=$data;


    //$plan=1;
    //if($article->flag==='P') $plan=2;
    $c['plan']=$article->plan;

    $c['flag'] = $article->flag;


    //echo "<!--frank".unique_id(10)."aa -->";
    $uid = unique_id(10);
    $c['uid'] = $uid;

    ob_start();
    enl_display($c); 

    $content = ob_get_contents();

    ob_end_clean();

    /*

    $dir = __APP__.'storage/frank/';
    if( !file_exists($dir) ){
        mkdir($dir, 0777);
    }
    */
    //file_put_contents( __APP__.'storage/frank/'.$uid , $content);

    //echo "<script>var uid='".$uid."';</script>";

    $content = replace_eznewlife($content);
    echo $content;


    exit;



    $fcache = \fcache::make();

    if(!$fcache->savefile){
        enl_display($c);
    }else{
        $fcache->start();
        enl_display($c);
        $fcache->stop();
        $fcache->save();
        echo $fcache->content;
    }

    exit;

    
    //print_r($c);return 'mobile';
    
}


function get_thumbnail_url($article){
    if(empty($article->photo)){
        return cdn("images/nophoto.png");
    }

    $photo = public_path().'/focus_photos/'.$article->photo;
    if(! \File::exists( $photo )) return cdn("images/nophoto.png");

    return cdn("focus_photos/400/".$article->photo);
}



function handle_summary($description){

    if(strlen(strip_tags($description))-mb_strlen(strip_tags($description))<10){
        // english 

        if(strlen(strip_tags($description))<99){
            return strip_tags($description);
        }

        return substr(strip_tags($description),0,98);

    }


    if(mb_strlen(strip_tags($description))<46){
        return strip_tags($description);
    }

    return mb_substr(strip_tags($description),0,45);

}


function my_handle_summary($description){
    $_e = '';
    if(mb_strlen($description) >30) $_e='...';
    return mb_substr($description,0,30,"UTF-8").$_e;
}

function mobile_index($data, $ArticleController, $page_status='is_home'){


    require __DIR__.'/eznewlife-mobile/functions.php';
    //use ff;

    // print_r($data->categories);exit;
    if($page_status === 'is_home'){
        $is_home=1;
        $is_category=0;
    }

    if($page_status === 'is_category'){
        $is_home=0;
        $is_category=1;
    }
    

    $c=[];

    $c['root_path'] = __ROOT__;
    $c['site_url'] = current_url().'/';
    $c['paged'] = $data->paged;
    $c['is_home']=$is_home;
    $c['is_single']=0;
    $c['is_category']=$is_category;
    $c['is_archive']=0;
    $c['is_404']=0;
    $c['page_status'] = $page_status;
    $c['title']='';
    $c['previous_link']='';
    $c['next_link']='';
    $c['max_paged']=$data->maxpaged;
    $c['content']='';
    $c['ad_block_1'] = '';
    $c['ad_block_2'] = '';
    $c['ad_block_3'] = '';
    $c['ad_block_4'] = '';
    $c['ad_block_5'] = '';
    $c['categories']=$data->categories;
    $maxpaged = $data->maxpaged;
    $paged = $data->paged;
    //$c['og_image'] = current_url().'/focus_photos/'.$article->photo;
    $c['og_image'] = current_url().'/images/index.png';

    

    $content = '';
    foreach($data->other_articles as $k => $d){
        $p = new \stdClass();

        $p->guid = route('articles.show', ['id'=>$d->ez_map[0]->unique_id,'title'=>hyphenize($d->title)],  false);
        $p->thumbnail_url = get_thumbnail_url($d);
        $p->date =  date('Y-m-d',strtotime($d->publish_at));
        $p->title = hyphenize($d->title);
        $p->alt = handle_summary($d->summary);
        $p->category = $data->categories[$d->category_id];
        $p->post_id = $d->ez_map[0]->unique_id;
        
        $content.='<a href="'.$p->guid.'" class="link" post_id="'.$p->post_id.'">';
        //$content.='<img src="'.$d['thumbnail_url'].'" />';
        //$content.=$p->thumbnail_url;
        $content.='<img width="800" height="500" src="'.$p->thumbnail_url.'" class="attachment-post-thumbnail wp-post-image" alt="A2-horz">';
        $content.='<p>';
        //$content.='<span class="tab">'.$d['category'].'</span><span class="date">'.$d['date'].' '.$d['time'].'</span><br />';
        $content.='<span class="tab">'.$p->category.'</span><span class="date">'.$p->date.'</span><br />';
        $content.='<span class="title">'.$p->title.'</span><br />';
        $content.='<span class="alt">'.$p->alt.'</span>';
        $content.='</p></a>';
    }

    $c['content']=$content;

    //$c['content'] = enl_get_content($c);
    //廣告
    $adv = \get_adv('', 'is_home');
    $c['ad_block_1'] = $adv['ad_block_1'];
    $c['ad_block_2'] = $adv['ad_block_2'];
    $c['ad_block_3'] = $adv['ad_block_3'];
    $c['ad_block_4'] = $adv['ad_block_4'];
    $c['ad_block_5'] = $adv['ad_block_5'];
    
    $c['title'] = 'EzNewlife-簡單新生活';
    $c['description'] = '簡單新生活';
    $c['current_page_url'] = URL('/');

    if(!empty($_POST['isAjax']) && $_POST['isAjax']==1){
        //echo $c['content'];
    }else{
        
    }


    //var_dump($maxpaged);

    if($page_status==='is_category'){
        if($data->paged != 1){
            $c['previous_link'] = route('articles.category',[$data->category_id,$data->categories[$data->category_id] ]).'?paged='.($data->paged-1);
        }else{
            $c['previous_link']=false;
        }

        if($maxpaged==0 || ($paged+1) <= $maxpaged){
            $c['next_link'] = route('articles.category',[$data->category_id,$data->categories[$data->category_id] ]).'?paged='.($data->paged+1);
        }else{
            $c['next_link'] = false;
        }
    }

    if($page_status==='is_home'){
        if($data->paged != 1){
            $c['previous_link'] = '/?paged='.($data->paged-1);
        }else{
            $c['previous_link']=false;
        }

        if($maxpaged==0 || ($paged+1) <= $maxpaged){
            $c['next_link'] = '/?paged='.($data->paged+1);
        }else{
            $c['next_link'] = false;
        }
    }

    $c['tpl'] = __DIR__.'/eznewlife-mobile/tpl/home.html';
    $c['is_admin'] = 0;
    $c['data']=$data;
    $c['plan']=1;
    

    
    ob_start();
    enl_display($c); 

    $content = ob_get_contents();

    ob_end_clean();


    $content = replace_eznewlife($content);
    echo $content;
    exit;
}


function enl_get_content_article($article_id){
    $conn=conn();
    $sql="select * from articles where id=:article_id";
    $row = $conn->getOne([ $sql, $article_id ], 'FETCH_OBJ');

    return $row;
}


function config($k, $v=null){
    static $dic=null;

    if($dic===null){
        $dic=[];
        //$file = __DIR__.'/config/'.__DOMAIN__.'.php';
        load_config(__DOMAIN__);
    }

    if($v===null){
        if(isset($dic[$k])) return $dic[$k];
        return false;
    }

    $dic[$k]=$v;
}

function load_config($filename){

    $file = __DIR__.'/config/'.$filename.'.php';

    if(file_exists($file)){
        $config=[];
        require $file;
        foreach($config as $k => $v){
            config($k, $v);
        }
    }
}
