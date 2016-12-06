<?php
class fb
{
    private $sys;
    public $appId;
    public $secret;
    public $facebook;
    public function __construct($p){
        $this->sys = $GLOBALS['sys'];
        $this->appId=$p['appId'];
        $this->secret=$p['secret'];
        require_once 'src/facebook/facebook.php';
        $this->facebook = new Facebook(array(
            'appId'=>$p['appId']
            ,'secret'=>$p['secret']
            ,'cookie'=>true
            ,'fileUpload'=>true
        ));
        $this->facebook->setFileUploadSupport(true);
    }
    public function init($app_id,$secret){
        $this->appId=$app_id;
        $this->secret=$secret;
        $this->facebook = new Facebook(array(
        'appId'  => $app_id,
        'secret' => $secret,
        'cookie'=>true,
        'fileUpload'=>true
        ));
        $this->facebook->setFileUploadSupport(true);  
    }
    public function api($method,$type,$param){
        $r = array();
        $r['method'] = $method;
        $r['param'] =$param;
        $r['error_msg'] = '';
        
        try{
            $ret_obj = $this->facebook->api($method, $type,$param);
            $r['success']=true;
        }catch(FacebookApiException $e){
            $r['error_msg'] = $e->getMessage();
            $r['success']=false;
        }
        return $r;
    }
    public function feed($user_id,$param){
        $sys = $this->sys;
        $facebook = $this->facebook;
        $r = array();
        $r['user_id'] = $user_id;
        $r['param'] = $param;
        $r['error_msg'] = '';
        try{
            $ret_obj = $facebook->api('/'.$user_id.'/feed', 'POST',$param);
        }catch(FacebookApiException $e){
            $r['error_msg'] = $e->getMessage();
        }
        return $r;
    }
    public function feed_curl($user_id,$param){
        $posturl = "https://graph.facebook.com/$user_id/feed";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $posturl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($ch);
        curl_close($ch); 
        print_r($r);
    }
    public function photos($user_id,$param){
        $sys = $this->sys;
        $facebook = $this->facebook;
        $r = array();
        $r['user_id'] = $user_id;
        $r['param'] = $param;
        $r['error_msg'] = '';
        try{
            $ret_obj = $facebook->api('/'.$user_id.'/photos', 'POST',$param);
        }catch(FacebookApiException $e){
            $r['error_msg'] = $e->getMessage();
        }
        return $r;
    }
    public function access_token(){
        return $this->facebook->getAccesstoken();
    }
    public function me(){
        $r = $this->facebook->api['me'];
        $r['access_token'] = $this->facebook->getAccesstoken();
        return $r;
    }
    public function login($scope,$redirect_uri){
        $fbid = $this->facebook->getUser();
        $r = array();
        if(!empty($fbid)){
            try{
                $r = $this->facebook->api('/me');
                $r['access_token'] = $this->facebook->getAccesstoken();
                //$accounts = $this->facebook->api('/me/accounts');
            }catch(FacebookApiException $e){
                $r['error_msg'] = $e->getMessage();
                $fbid = null;
            }
        }
        if(empty($fbid)){
            $loginUrl = $this->facebook->getLoginUrl(array(
                'scope' => $scope
                ,'redirect_uri' => $redirect_uri
            ));
            //print_r($r);
            //echo $loginUrl;
            header("Location:".$loginUrl);
        }
        return $r;
    }
    public function logout($redirect_uri){
        $params = array('redirect_uri' => $redirect_uri);
        $logoutUrl =$this->facebook->getLogoutUrl($params);
        //echo $logoutUrl;
        //sleep(3);
        header("Location:".$logoutUrl);
    }
    public function import(){
        //匯入使用者資訊
        $sys = $this->sys;
        $facebook = $this->facebook;
        $accesstoken = $facebook->getAccessToken();
        $user_profile = $facebook->api('/me', 'GET');
        if($user_profile['id']){
            $sql = "select id from user_info where fb_id='".$user_profile['id']."'";
            $rs = $sys->query($sql);
            $row = mysql_fetch_row($rs);
            if($row[0]){
                $sql = "UPDATE user_info SET accesstoken='".$accesstoken."' WHERE fb_id='".$user_profile['id']."'";
                $sys->query($sql);
            }else{
                $sql = "INSERT INTO user_info SET fb_id='".$user_profile['id']."'";
                $sql.=",name='".$user_profile['name']."'";
                $sql.=",gender='".$user_profile['gender']."'";
                $sql.=",accesstoken='".$accesstoken."'";
                $sql.=",user_profile='".json_encode($user_profile)."'";
                //file_put_contents('sql_log.php',$sql);
                $sys->query($sql);
            }
        }
    }
    public function long_access_token($access_token){
        $url="https://graph.facebook.com/oauth/access_token?";
        $url.="client_id=".$this->appId.'&';
        $url.="client_secret=".$this->secret.'&';
        $url.="grant_type=fb_exchange_token&";
        $url.="fb_exchange_token=".$access_token;
        //echo $url;return $access_token;
        $r = file_get_contents($url);
        
        $r = explode('&',$r);
        $r = $r[0];
        $r = explode('=',$r);
        $r = $r[1];
        //echo $r;
        return $r;
    }
    public function query($fql){
        $sys=$this->sys;
        //$sys->debug($fql);
        $param=array(
        'method'=>'fql.query',
        'query'=>$fql
        );
        try{
            $ret=$this->facebook->api($param);
        }catch(FacebookApiException $e){
            $sys->debug($e->getMessage());
        }
        return $ret;
    }
    public function multiquery($fqls){
        $sys=$this->sys;
        //$sys->debug($fqls);
        $param = array(
        'method'=>'fql.multiquery',
        'queries'=>$fqls
        );
        try{
            $ret=$this->facebook->api($param);
        }catch(FacebookApiException $e){
            $sys->debug($e->getMessage());
        }
        
    }
}
?>