<?php 

class eyny_com{

    public $username='cutey537';

    public $password='scwqx5y5';

    public $reqs=[];

    public $debug=false;


    public function __construct($url){
        $this->url = $url;
        $this->host = parse_url($url,PHP_URL_HOST);
    }


    public function response(){
        $req = $this->r0();
        $this->r1($req);

        if(!$this->isLogin($req) || $this->isAdultCheck($req)){
            //echo 3;
            $formData = $this->loginPrepare($req);
            //sleep(20);
            $this->login($formData);

            $req = $this->r2($req);

            $req = $this->makeReq($this->url, 'GET');
            $req->response();
            
            $this->r1($req);
        }

        return $req->response;
    }


    public function response_bk(){

        $req = $this->r0();

        $this->r1($req);


        if($this->isAdultCheck($req)){
            $req = $this->r2($req);
        }

        //return $req->response;

        //return $response;
        if(!$this->isLogin($req)){
            //echo 3;
            $formData = $this->loginPrepare($req);
            //sleep(20);
            $this->login($formData);

            $req = $this->r0();
        }

        return $req->response;
        

    }




    protected function r0(){
        $req = $this->makeReq($this->url, 'GET');
        $req->clearCookie();
        $req->response();
        $this->reqs['0'] = $req;
        return $req;
    }




    protected function r1($request){
        $url = $this->getUrl_1($request->response);
        $req = $this->makeReq($url, 'GET');
        $req->setHeader('Referer', $this->url);
        $req->setHeader('Origin', 'http://www256.eyny.com');
        $req->response();
        $this->reqs['1'] = $req;
        return $req;

    }




    // adult Agree
    protected function r2($request){
        $req = $this->makeReq($this->url, 'POST', ['agree'=>'yes', 'submit'=>'是，我已年滿18歲。 '."\n".'Yes, I am.']);
        $req->setHeader('Referer', $this->url);
        $req->setHeader('Origin', 'http://www256.eyny.com');
        //$req->setCookie('__asc', '5cca0fe6154184ecd892f86ebe0');
        //$req->setCookie('__auc', '5cca0fe6154184ecd892f86ebe0');
        $req->response();
        $this->reqs['2']=$req;
        return $req;
    }




    protected function getUrl_1($response){
        $query = file_get_html($response);

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



    protected function isAdultCheck($req){
        
        if(strpos($req->response, '本網站已依網站內容分級規定處理') === false) return false;

        return true;
    }



    protected function isLogin($req){

        if(strpos($req->response, '登入會員') === false){
            return true;
        }

        return false;
    }


// http://www256.eyny.com/member.php?mod=logging&action=login&loginsubmit=yes&handlekey=login&loginhash=LvyE3&inajax=1

/*
get
mod:logging
action:login
loginsubmit:yes
handlekey:login
loginhash:LvyE3
inajax:1

post

formhash:6808f7f9
referer:http://www256.eyny.com/thread-10828695-1-3DN3CFFH.html
loginfield:username
username:
password:
questionid:0
answer:
cookietime:2592000
loginsubmit:true
*/





// http://www256.eyny.com/member.php?mod=logging&action=login&infloat=yes&handlekey=login&inajax=1&ajaxtarget=fwin_content_login
    
    
    protected function loginPrepare($request){
        $response = $request->response;

        $formData = $this->getFormData();
        $formData->loginfield='username';
        $formData->username = $this->username;
        $formData->password = $this->password;
        $formData->questionid='0';
        $formData->answer='';
        $formData->loginsubmit='true';
        return $formData;
    }


    protected function login($formData){

        $url='http://www256.eyny.com/member.php?mod=logging&action=login&loginsubmit=yes&handlekey=login&loginhash=LlsV1&inajax=1';

        $data = new stdClass;
        $data->formhash='6808f7f9';
        $data->referer='http://www256.eyny.com/thread-10828695-1-3DN3CFFH.html';
        $data->loginfield='username';
        $data->username='cutey537';
        $data->password='scwqx5y5';
        $data->questionid='0';
        $data->answer='';
        $data->cookietime='2592000';
        $data->loginsubmit='true';

        $req = $this->makeReq($url, 'POST', $data);


        $req->setHeader('Accept','text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8');
        $req->setHeader('Accept-Language','zh-TW,zh;q=0.8,en-US;q=0.6,en;q=0.4,zh-CN;q=0.2,es;q=0.2,ja;q=0.2,ru;q=0.2,fr;q=0.2,it;q=0.2,gl;q=0.2');
        $req->setHeader('Cache-Control','no-cache');
        $req->setHeader('Connection','keep-alive');

        $req->setHeader('Upgrade-Insecure-Requests','1');
        $req->setHeader('User-Agent','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36');

        $req->setHeader('Cookie', 'djAX_e8d7_lastvisit=1460943992; djAX_e8d7_sendmail=1; __utmt=1; djAX_e8d7_agree=206; __utma=144401971.801710169.1460947597.1460947597.1460947597.1; __utmb=144401971.2.10.1460947597; __utmc=144401971; __utmz=144401971.1460947597.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __asc=f80d402a154274336398f1183d7; __auc=f80d402a154274336398f1183d7; djAX_e8d7_lastact=1460947603%09member.php%09logging');

        $req->response();

        return $req;
    }


    protected function login_bk($formData){

        $url = $this->url;

        if(strpos($url, '?') === false) $url.='?';
        else $url.='&';



        $url = 'http://'.$this->host.'/'.$formData->action.'&inajax=1';
        
        unset($formData->action);
        unset($formData->referer);

        $formData->referer = $this->url;

        $req = $this->makeReq($url, 'POST', $formData);
        $req->setHeader('Referer', $this->url);
 
        $req->response();

        //return $req;
        $req = $this->makeReq($this->url, 'GET');

        $req->setHeader('Accept','text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8');

        $req->response();

        return $req;



    }




    protected function getFormData(){

        $data=new stdClass;

        $url='http://www256.eyny.com/member.php?mod=logging&action=login&infloat=yes&handlekey=login&inajax=1&ajaxtarget=fwin_content_login';
        //$url='http://www256.eyny.com/member.php?mod=logging&action=login&loginsubmit=yes&handlekey=login&loginhash=LGQ6b&inajax=1';
        $req = $this->makeReq($url, 'GET');
        $r = $req->response();


        $r = str_replace("<?xml version='1.0' encoding='UTF-8'?>", '', $r);
        $r = str_replace('<root><![CDATA[', '', $r);
        $r = str_replace('</root>', '', $r);

        $query = file_get_html($r);

        $form = $query->find('form')[0];

        $data->action = $form->getAttribute('action');

        $ds = $form->find('input');

        for ($i=0,$imax=count($ds); $i < $imax; $i++) { 
            $d=$ds[$i];
            $name=$d->name;

            $data->$name = $d->getAttribute('value');
        }

        return $data;



    }



    protected function makeReq($url, $method='GET', $param=null){
        $req = new __request($url, $method, $param);
        $req->debug=$this->debug;
        $req->setHeader('Origin', 'http://www256.eyny.com');

        return $req;
    }


}