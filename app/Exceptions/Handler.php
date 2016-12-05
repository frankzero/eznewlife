<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Symfony\Component\Debug\ExceptionHandler as SymfonyDisplayer;
use Debugbar,Request;
use Storage,App\Parameter;
use Jenssegers\Agent\Agent;
use Cache;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
       // Log::error( '['.$e->getCode().'] "'.$e->getMessage().'" on line '.$e->getTrace()[0]['line'].' of file '.$e->getTrace()[0]['file']);
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
       
       //$parameter = Parameter::enl()->lists('data', 'name');
       $parameter =cache_get('enl_parameters')->toArray();
      // dd(Request::server('SERVER_ADDR'));
      //59.126.180.51
        $agent = new Agent();
        if ($agent->isRobot()) $robot="機器人";else $robot='';
        $exists= Storage::has('route.txt');
        if ($exists) {

            $size = Storage::size('route.txt'); //dd($size);
            if ($size > 1024000) Storage::delete('route.txt');
        }
         $pass[]="apple-touch-icon-precomposed.png";
         $pass[]="apple-touch-icon.png";
         $pass[]="apple-touch-icon-120x120.png";
         $pass[]="apple-touch-icon-120x120-precomposed.png";
        $pass_log=false;
        foreach ($pass as $k=>$v) {
            $nolog=preg_match("/$v/i", Request::url());
            //dd("/$v/i".$nolog);
            if ($nolog){ 
                $pass_log=true;
                
            }
        }

        if ( $pass_log==false    and (isset($parameter['route_log']) and $parameter['route_log']==1 )) {
            $exists= Storage::has('route.txt');
            if ($exists) {
                $size = Storage::size('route.txt'); //dd($size);
                if ($size > 1024000) Storage::delete('route.txt');
                
            }
            
             
            //Storage::prepend("route.txt",date("Y-m-d H:i:s")."  ".$robot."".$agent->device()." ". Request::url()." ".$request->ip());

            $err=[];
            $err[] = date("Y-m-d H:i:s")."  ".$_SERVER['REQUEST_METHOD'].' '.$robot." ".$agent->device();
            $err[] = 'Referer:'. $_SERVER['HTTP_REFERER']; 
            $err[] = $_SERVER['HTTP_USER_AGENT'];
            $err[] = urldecode(Request::url());
            $err[] = $request->ip();
            $err[] = $e->getMessage();
            $err[] = $e->getFile().' '.$e->getLine();
            // $err[] = $e->getTraceAsString();
            // $err[] = 'cookie:'.print_r($_COOKIE, true);
            // $err[] = 'session:'.print_r($_SESSION, true);
            // $err[] = 'server:'.print_r($_SERVER, true);
            // $err[] = 'header:'.print_r(getallheaders (), true);
            $err[] = '<hr style="border-color:red;" />';
            
            $err = implode("\n", $err)."\n\n"; 

            Storage::prepend("route.txt",$err);
                
        }


        if (config('app.debug') == true) {


            if ($e instanceof ModelNotFoundException) {
                $e = new NotFoundHttpException($e->getMessage(), $e);
            }
            return parent::render($request, $e);

        } else {
          //  redirect()->away('https://www.dropbox.com')
            if (preg_match("@\.(gif|jpe?g|png)$@",  Request::url())) {
                $type="application/xml";
                $xml='<Error>
                <Code>AccessDenied</Code>
                <Message>Access Denied</Message>
                </Error>';


                return response($xml)->setStatusCode(404)
                    ->header('Content-Type', $type);
                //return response()->json(['code' => '302', 'state' => 'image not found']);
            }else {
                /*
                $type="application/xml";
                $xml='<Error>
                <Code>AccessDenied</Code>
                <Message>Access Denied</Message>
                </Error>';
                return response($xml)->setStatusCode(404)
                    ->header('Content-Type', $type);
                */
                return redirect()->to(url("/404notfound"));
            }
            /*
            if (strpos(Request::server('SERVER_NAME'),'getez.info') !== false) {
                return redirect()->route('getezs.error');
            }else if (strpos(Request::server('SERVER_NAME'),'dark.eznewlife.com') !== false){
                return redirect()->route('darks.error');
            }else if (strpos(Request::server('SERVER_NAME'),'avbody.info') !== false){
                return redirect()->route('comics.error');
            } else {
                return redirect()->route('articles.error');
            }
            }*/
        }
    }

    /*
     *  測試環境呈現錯誤方式
     */

    protected function renderHttpException(HttpException $e)
    {

        $status = $e->getStatusCode();

        Debugbar::addMessage('主機',Request::server('SERVER_ADDR'));
        Debugbar::addMessage('path',  Request::getPathInfo());
        Debugbar::addMessage('主回應訊息', $e->getStatusCode(). " / ".$e->getFile()." on ".$e->getLine());
        $message= $e->getTrace()[1]['file']." on " .$e->getTrace()[1]['line']."\n function = ".$e->getTrace()[1]['function'];

       Debugbar::addMessage('可能問題發生在 ', $message);
        if ($status == "404") {
            Debugbar::error($e->getMessage());
            echo "<pre style='text-align: center'>".$message."</pre>";
            //Debugbar::error('Error!');
            return (new SymfonyDisplayer(config('app.debug')))->createResponse($e);
            /*
        } elseif (view()->exists("errors.{$status}")) {
            return response()->view("errors.{$status}", compact('e'), $status);
            */
        } else {

            return (new SymfonyDisplayer(config('app.debug')))->createResponse($e);
        }
    }

}
