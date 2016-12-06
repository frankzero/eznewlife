<?php
class SysObject 
{
    private $global_obj;
    
    private $connect;
    
    private $connecti;
    
    private $json;
    
    private $show_mode;
    
    private $user_agent;
    
    private $ip;
    
    private $_current_url;
    
    private $load_files;
    
    public $loadcount = 0;
    
    public $config;
    
    public $global_var;
    
    public $site;
    
    // 偵測平板 tablet , 手機 phone , 電腦 computer
    public $deviceType; 
    
    // 偵測平板 2 , 手機 1 , 電腦 0
    public $deviceCode; 
    
    public $is_mobile;
    
    // 0 一般 1 工程師 
    public $debug_mode;
    
    public $sqls;
    
    function __construct($config)
    {
        error_reporting(E_ALL);
        //預設值
        if(empty($config['adminip']))$config['adminip'] = array();
        $this->config = $config;
        if(!empty($config['site']))$this->site=$config['site'];
        $this->debug_mode=false;
        
        if(in_array($this->getip(),$this->config['adminip'])) $this->debug_mode=true;
        else if(!empty($_COOKIE['debug']) && $_COOKIE['debug']=='frank') $this->debug_mode=true;
        
        
        if($this->debug_mode==true){
            //error_reporting(E_ALL ^ E_NOTICE);
            error_reporting(E_ALL);
        }else{
            error_reporting(0);
        }
        $this->global_var=array();
        $this->global_obj=array();
        $this->dirname = dirname(__FILE__).'/';
        $this->show_mode=-1;
        $detect = $this->objects(array('construct'=>'Mobile_Detect'));
        $this->deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
        $this->deviceCode = ($detect->isMobile() ? ($detect->isTablet() ? 2 : 1) : 0);
        $this->is_mobile = ($this->deviceCode===0?false:true);
        $this->sqls=array();
        $this->connect = array();
        $this->connecti = array();
        $this->load_files = array();
    }
    
    public function init($show_mode){
        $this->show_mode = $show_mode;
    }
    
    public function objects($p=array()){
        $construct = $p['construct'];
        include_once($this->dirname.$construct.'.php');
        return (new $construct($p));
    }
    
    public function objects_bk($p=array()){
        $construct = $p['construct'];
        if(empty($this->global_obj[$construct])){
            if(file_exists($this->dirname.$construct.'.php')){
                include_once($this->dirname.$construct.'.php');
                $this->global_obj[$construct] = new $construct($p);
            }else{
                return null;
            }
        }
        return $this->global_obj[$construct];
    }
    
    public function getconnectioni($name,$file=''){
        //if($file=='')$file = '/home/'.$this->site.'/db.php';
        //include($file);
        //$c = $database[$name];
        $c = array('host'=>'localhost','username'=>'ezgo','password'=>'vhHh61G5OFDQOve88NsJ','dbname'=>'ezgo');
        $conn = new iconn($c['host'], $c['username'], $c['password'],$c['dbname']);
        return $conn;
    }
    
    public function getconntioni($name,$file=''){
        return $this->getconnectioni($name,$file);
    }
    
    public function iconnectionv2($name,$file=''){
        if($file=='')$file = '/home/'.$this->site.'/db.php';
        include_once('mysqlidb.php');
        include($file);
        $c = $database[$name];
        
        $conn = new Mysqlidb($this, $c['host'], $c['username'], $c['password'],$c['dbname']);
        return $conn;
    }
    
    public function getip()
    {
        if(!$this->ip){
            if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
                $myip = (!empty($_SERVER['REMOTE_ADDR']))?$_SERVER['REMOTE_ADDR']:'';
            } else {  
                $myip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);  
                $myip = $myip[0];  
            }
            $this->ip=$myip;
        }
        return $this->ip;
    }
    
    public function is_email($email){
        $v = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
        return (bool)preg_match($v, $email);
    }
    
    public function debug($ary,$is_query=false)
    {
        if($this->debug_mode==true){
            $this->show($ary,$this->show_mode);
            //$this->show(func_get_args(),$this->show_mode);
        }
    }
    
    public function show($text,$type='0'){
        switch($type)
        {
        case -1:
            break;
        case 0:
            echo "\n/*";print_r($text);echo "*/";
            break;
        case 1:
            echo "\n<!--";print_r($text);echo "-->";
            break;
        case 2:
            echo "\n";print_r($text);
            break;
        default:
            echo "\n/*";print_r($text);echo "*/";
            break;
        }
        
    }
    
    public function print_r($text){
        echo "<pre>";
        print_r($text);
        echo "</pre>";
    }
    
    public function unique_id($num)
    {
        $t = array(
        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
        ,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'
        ,'0','1','2','3','4','5','6','7','8','9'
        );
        $r = array();
        for($i=0;$i<$num;$i++)
        {
            $r[] = $t[rand(0,61)];
        }
        return implode('',$r);
    }
    
    public function importJS($jsPath,$cssPath){
        //載入js
        $path = $jsPath.'view/';
        $files = glob($path.'*.js');
        $content='';
        for($i=0,$imax=count($files);$i<$imax;$i++)
        {
            $f=$files[$i];
            if($f!='view.sample.js' && $f!='')
            $content.=file_get_contents($path.$f);
        }
        $path = $jsPath.'model/';
        $files = glob($path.'*.js');
        for($i=0,$imax=count($files);$i<$imax;$i++)
        {
            $f=$files[$i];
            if($f!='model.sample.js' && $f!='')
            $content.=file_get_contents($path.$f);
        }
        
        $content = $this->minify_js($content);
        header("Content-Type:text/html; charset=utf-8");
        echo $content;
        file_put_contents($jsPath.'all.js',$content, LOCK_EX);

        //css
        $content = '';
        $path = $cssPath.'view/';
        $files = glob($path.'*.css');
        for($i=0,$imax=count($files);$i<$imax;$i++)
        {
            $f=$files[$i];
            if($f!='')
            $content.=file_get_contents($path.$f);
        }
        file_put_contents($cssPath.'all.css',$content, LOCK_EX);
    }
    
    public function package_js($path,$files,$output){
        // 路徑 檔案 輸出檔案
        $content='';
        for($i=0,$imax=count($files);$i<$imax;$i++){
            $f=$files[$i];
            $f = $path.$f;
            if(file_exists($f)){
                $content.=file_get_contents($f);
            }
        }
        $r = $this->minify_js($content);
        file_put_contents($path.$output,$r);
    }
    
    public function package_css($path,$files,$output){
        // 路徑 檔案 輸出檔案
        $content='';
        for($i=0,$imax=count($files);$i<$imax;$i++){
            $f=$files[$i];
            $f = $path.$f;
            if(file_exists($f)){
                $content.=file_get_contents($f);
            }
        }
        $r = $this->minify_js($content);
        file_put_contents($path.$output,$r);
    }
    
    public function minify_js($content){
        //做壓縮
        $postdata = array();
        $postdata[]=array('js_code',$content);
        $postdata[]=array('compilation_level','SIMPLE_OPTIMIZATIONS');
        $postdata[]=array('output_format','json');
        $postdata[]=array('output_info','compiled_code');
        $postdata[]=array('output_info','errors');
        $postdata[]=array('output_info','warnings');
        $postdata[]=array('output_info','statistics');
        $postdata[]=array('output_file_name','default.js');
        $content = $this->doPost('closure-compiler.appspot.com','http://closure-compiler.appspot.com/compile',$postdata);
        //echo $content;
        $output = json_decode($content,ture);
        $content = $output['compiledCode'];
        return $content;
    }
    
    public function minify_css($buffer){
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
    
    public function current_url(){
        if(!$this->_current_url){
            $pageURL = 'http';
            if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
            $pageURL .= "://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]; // REQUEST_URI ,PHP_SELF
            } else {
                $pageURL .= $_SERVER["SERVER_NAME"];
            }
            $this->_current_url = $pageURL;
        }
        return $this->_current_url;
    }
    
    public function ret($success,$msg,$data){
        return array(
        'success'=>$success
        ,'msg'=>$msg
        ,'data'=>$data
        );
    }
    
    public function getbrowser(){
        if(!$this->user_agent){
            $this->user_agent=$this->_getBrowser();
        }
        return $this->user_agent;
    }
    
    //擷取字串
    public function truncate($string,$max){
        if(mb_strlen($string, 'utf-8') >= $max){
        //if(strlen($string) >= $max){
            $string = mb_substr($string, 0, $max, 'utf-8').'...';
            //$string = substr($string, 0, $max).'...';
        }
        //echo $string.'-'.strlen($string).'-'.$max."<br>";
        return $string;
    }
    
    public function post_request($url, $data, $referer='') {
        
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
        
        // open a socket connection on port 80 - timeout: 30 sec
        $fp = fsockopen($host, 80, $errno, $errstr, 30);
        
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
    
    public function doPost($url,$urlPath,$post_data,$type=0)
    {
        $response='';
        $post_string = '';
        if($type==0){
            $o="";
            //foreach ($post_data as $k=>$v)
            for($i=0,$imax=count($post_data);$i<$imax;$i++)
            {
                $p=$post_data[$i];
                $o.= "$p[0]=".urlencode($p[1])."&";
                //$o.= "$k=".$v."&";
            }
            $post_string=substr($o,0,-1);
        }else{
            $post_string = $post_data;
        }
        
        $fp = fsockopen($url, 80, $errno, $errstr,3);
        if (!$fp)
        {
            $this->show($this->url.''.$urlPath.'?'.$post_string,'fsockopen Error');
            return "fsockopen Error";
        }
        else
        {
            $da ="POST $urlPath  HTTP/1.1\r\n";
            $da.="Host: $url \r\n";
            $da.="User-Agent: PHP Script\r\n";
            $da.="Content-Type: application/x-www-form-urlencoded\r\n";
            $da.="Content-Length: ".strlen($post_string)."\r\n";
            $da.="Connection: close\r\n\r\n";
            $da.=$post_string;
            
            fwrite($fp, $da);
            while (!feof($fp))
            {
                $response.=fgets($fp, 128);
            }
            $response = explode("Connection: close",$response);
            return trim($response[1]);
        }
    }
    
    public function curl_post($url,$post,$referer=''){
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
    
    public function api($p){
        $api_path=$p['api_path'];
        $cmd = $p['cmd'];
        $this->debug($cmd);
        $recording = @$p['recording'] OR false;
        $onlydata = @$p['onlydata'] OR false;
        
        $cmd = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $cmd);
        $file = $api_path.$cmd.'.php';
        $file2 = $api_path.'api.'.$cmd.'.php';
        $response = array();
        $api_message = '';
        
        if(file_exists($file)){
            $response = include($file);
        }else if(file_exists($file2)){
            $response = include($file2);
        }else{
            $response =  $this->ret(0,'error 01','');
        }
        if($recording){
            //$this->api_log($cmd,$api_path,$response,$api_message);
        }
        
        if($onlydata){
            $response = $response['data'];
        }
        return $response;
    }
    
    public function api_log($cmd,$api_path,$r,$api_message){
        $r = array(
            'GET'=>$_GET
            ,'POST'=>$_POST
            ,'response'=>$r
            ,'api_message'=>$api_message
            ,'url'=>'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]
            ,'query'=>$this->sqls
            ,'addtime'=>time()
        );
        $db_path = realpath($api_path.'../').'/database/apidescription_'.$cmd;
        file_put_contents($db_path,json_encode($r));
        //file_put_contents($api_path.'log.txt',json_encode($r)."^^^_^^^\n",FILE_APPEND);
        
    }
    
    public function is_token($token){
        //判斷是 數字+字母
        if (preg_match ("/^[a-z0-9]+$/i", $token)) {
            return true;
        }else{
            return false;
        }
    }
    
    //顯示樣板
    public function display($p,$ob=false){
        $p = (object)$p;
        $tpl = $p->tpl;
        $tpl = preg_replace("/[^a-zA-Z0-9_\-\.]+/", "", $tpl); // XSS protection as we might print this value
        //$path = $p['path'];
        $tpl = NOWPATH.'tpl/'.$tpl;
        if($ob===true){
            ob_start();
        }
        include($tpl); 
        if($ob===true){
            $r = ob_get_contents();
            ob_get_clean();
            return $r;
        }else{
            return '';
        }
    }
    
    //執行php
    public function exec_code($p){
        $p = (object)$p;
        $file = $p->file;
        $file = preg_replace("/[^a-zA-Z0-9_\-\.]+/", "", $file); // XSS protection as we might print this value
        $file = NOWPATH.'exec/'.$file;
        return include($file);
    }
    
    // 同步資料夾內的檔案 (不包含子資料夾)
    public function sync_dir($path1,$path2){
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
    
    // 載入 js
    public function requireJS($jsPath,$minifier=false,$tt='1'){
        $content='';
        if($minifier){
            $content.='<script type="text/javascript" src="'.$jsPath.'all.js?tt='.$tt.'"></script>'."\n";
        }else{
            $path = $jsPath.'view/';
            $files = glob($path.'*.js');
            $content='';
            for($i=0,$imax=count($files);$i<$imax;$i++)
            {
                $f=$files[$i];
                if($f!='')$content.='<script type="text/javascript" src="'.$f.'?tt='.$tt.'"></script>'."\n";
            }
            $path = $jsPath.'model/';
            $files = glob($path.'*.js');
            for($i=0,$imax=count($files);$i<$imax;$i++)
            {
                $f=$files[$i];
                if($f!='')$content.='<script type="text/javascript" src="'.$f.'?tt='.$tt.'"></script>'."\n";
            }
        }
        return $content;
    }
    
    // 載入css
    public function requireCSS($cssPath,$minifier=false,$tt='1'){
        $content='';
        if($minifier){
            $content.='<link rel="stylesheet" type="text/css" href="'.$cssPath.'all.css?tt='.$tt.'" />'."\n";
        }else{
            $path = $cssPath.'view/';
            $files = glob($path.'*.css');
            for($i=0,$imax=count($files);$i<$imax;$i++)
            {
                $f=$files[$i];
                if($f!='')$content.='<link rel="stylesheet" type="text/css" href="'.$f.'?tt='.$tt.'" />'."\n";
            }
        }
        return $content;
    }
    
    private function _getBrowser(){
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes separately and for good reason.
        if (preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif (preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif (preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif (preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif (preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif (preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }
        else{
            //$bname = $u_agent;
            //$ub = $u_agent;
        }

        // Finally get the correct version number.
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // See how many we have.
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }

        // Check if we have a number.
        if ($version==null || $version=="") {$version="?";}
        if (!$ub){
            //$bname = $u_agent;
            //$ub = $u_agent;
        }

        return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'sname'      => $ub,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
        );
    }
    
    /*
        偵測只載入一次 (因為 require_once 效能不好 所以改寫)
        一定要用完整路徑
    */
    public function load_once($file){
        if(empty($this->load_files[$file])){
            if($this->debug_mode==1 && !file_exists($file)){
                debug_print_backtrace();
            }
            $this->loadcount++;
            include($file);
            $this->load_files[$file] = true;
        }
    }
    
    public function doHash($password,$key=''){
        return hash('sha512', $password.$key); // hash the password with the unique salt.
    }
}

include('iconn.php');

if(empty($config)) $config=array();
$sys = new SysObject($config);

?>
