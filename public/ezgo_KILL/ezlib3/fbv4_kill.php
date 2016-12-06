<?php
class fbv4
{
    private $sys;
    
    public $appId;
    
    public $secret;
    
    public $facebook;
    
    public function __construct($p){
        $this->sys = $GLOBALS['sys'];
        $this->appId=$p['appId'];
        $this->secret=$p['secret'];
        
        $facebookpath = 'src/facebook-php-sdk-v4-master/src/Facebook/';
        require_once( $facebookpath.'FacebookSession.php' );
        require_once( $facebookpath.'FacebookRedirectLoginHelper.php' );
        require_once( $facebookpath.'FacebookCanvasLoginHelper.php' );
        require_once( $facebookpath.'FacebookRequest.php' );
        require_once( $facebookpath.'FacebookResponse.php' );
        require_once( $facebookpath.'FacebookSDKException.php' );
        require_once( $facebookpath.'FacebookRequestException.php' );
        require_once( $facebookpath.'FacebookAuthorizationException.php' );
        require_once( $facebookpath.'GraphObject.php' );
        
        use Facebook\FacebookSession;
        use Facebook\FacebookRequest;
        use Facebook\GraphUser;
        use Facebook\FacebookRequestException;
        
        FacebookSession::setDefaultApplication($this->config['app_id'],$this->config['secret']);
        
    }
    
    
}
?>