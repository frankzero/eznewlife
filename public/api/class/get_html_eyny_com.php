<?php 

class get_html_eyny_com{

    public $username='cutey537';

    public $password='scwqx5y5';

    public $debug=false;

    public $html='';

    public $testfile='';

    public function __construct($url){
        $this->url = $url;
        $this->host = parse_url($url,PHP_URL_HOST);

        $this->testfile = sys_get_temp_dir().'/'. 'test_'.md5($this->client_ip().$this->host);
    }



    public function test(){


        // $url = 'http://www256.eyny.com/thread-10828695-1-3DN3CFFH.html'; 


        if(http::get('r') == '1'){
            echo 'firstRequest';
            $this->firstRequest();
            echo $this->req->response;
            file_put_contents($this->testfile, $this->req->response);


            return;
        }

        $this->html = file_get_contents($this->testfile);

        if(http::get('r') == '2'){
            echo 'firstRequest_1';
            $this->firstRequest_1();
            echo $this->req->response;
            file_put_contents($this->testfile, $this->req->response);
            return;
        }


        if(http::get('r') == '3'){
            echo 'adultAgree';
            $this->adultAgree();
            echo $this->req->response;
            file_put_contents($this->testfile, $this->req->response);
            return;
        }


        if(http::get('r') == '4'){
            echo 'loginFormRequest';
            $this->loginFormRequest();
            echo $this->req->response;
            file_put_contents($this->testfile, $this->req->response);
            return;
        }

        if(http::get('r') == '5'){
            echo 'login';
            $this->login();
            echo $this->req->response;
            file_put_contents($this->testfile, $this->req->response);
            return;
        }
    }



    public function response(){

        $this->get_html();

        if( !$this->isAdultCheck()  &&  $this->isLogin()){
            return $this->html;
        }

        
        setSatus('cookie蒐集',0);
        $this->firstRequest();
        $this->firstRequest_1();

        setSatus('18歲同意',0);
        $this->adultAgree();

        setSatus('cookie模擬',0);
        $this->loginFormRequest();
        setSatus('登入',0);
        $this->login();
        
        return $this->get_html();

    }



    protected function get_html(){
        $req = $this->makeReq($this->url, 'GET');
        $req->response();
        $this->html=$req->response;
        return $req->response;
    }



    protected function firstRequest(){
        $req = $this->makeReq($this->url, 'GET');
        $req->clearCookie();
        $html = $req->response();
        
        $this->html = $html;

   }



    protected function firstRequest_1(){

        
        $url = $this->get_url_homephp($this->html);
        $req = $this->makeReq($url, 'GET');
        $req->response();

    }



    protected function get_url_homephp($html){

        $query = file_get_html($html);
        
        $ss = $query->find('script');

        for ($i=0,$imax=count($ss); $i < $imax; $i++) {
            $s=$ss[$i];

            $path = $s->getAttribute('src');

            if( strpos($path, 'home.php') !== false ){
                return 'http://'.$this->host.'/'.$path;
            }
        }

        return false;
    }



    protected function adultAgree(){
        $req = $this->makeReq($this->url, 'POST', ['agree'=>'yes', 'submit'=>'是，我已年滿18歲。 '."\n".'Yes, I am.']);
        $req->response();
        $this->html = $req->response;
    }



    protected function login(){

        preg_match('/<form([\s\S.]*)form>/iU',$this->html,$match);

        $html = $match[0];

        $form = file_get_html($html)->find('form')[0];



        $data=new stdClass;
        $data->action = $form->getAttribute('action');

        $ds = $form->find('input');

        for ($i=0,$imax=count($ds); $i < $imax; $i++) { 
            $d=$ds[$i];
            $name=$d->name;

            $data->$name = $d->getAttribute('value');
        }

        $formData=$data;
        $formData->loginfield='username';
        $formData->username = $this->username;
        $formData->password = $this->password;
        $formData->questionid='0';
        $formData->answer='';
        $formData->loginsubmit='true';
        $formData->referer = $this->url;

        $url = 'http://'.$this->host.'/'.$formData->action.'&inajax=1';
        //$url='http://www256.eyny.com/member.php?mod=logging&action=login&loginsubmit=yes&handlekey=login&loginhash=L7Pwg&inajax=1';
        $url=htmlspecialchars_decode($url);

        unset($formData->action);
        unset($formData->referer);

        $req = $this->makeReq($url, 'POST', $formData);
        $req->setCookie('username',$this->username);
        $req->response();

    }


    protected function loginFormRequest(){
        $url='http://'.$this->host.'/member.php?mod=logging&action=login&infloat=yes&handlekey=login&inajax=1&ajaxtarget=fwin_content_login';
        $req = $this->makeReq($url, 'GET');
        $this->html = $req->response();
    }



    protected function isAdultCheck(){
        
        if(strpos($this->html, '本網站已依網站內容分級規定處理') === false) return false;

        return true;
    }



    protected function isLogin(){

        if(strpos($this->html, '登入會員') === false){
            return true;
        }

        return false;
    }


    protected function makeReq($url, $method='GET', $param=null){
        $req = new __request($url, $method, $param);
        $req->debug=$this->debug;
        $req->setHeader('Referer', $this->url);
        $req->setHeader('Origin', 'http://'.$this->host);

        $this->req=$req;

        return $req;
    }


    protected static function client_ip(){
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

}