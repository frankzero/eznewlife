<?php

namespace App\Http\Controllers\Auth;

use App\User, App\AvUser, App\EnlUser;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Auth,Config;
use Socialite;
use Request;
use URL;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    //預設成功導入
    // private $redirectPath = '/';


    // protected $redirectTo = '/admin/articles/datatables';

    protected $username = 'name';
    //失敗
    //protected $loginPath = '/login';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     **/
    public function __construct()
    {
        if (strpos(Request::server('SERVER_NAME'), 'avbody.info') !== false) {

            $this->redirectPath = '/';
        } else if (strpos(Request::server('SERVER_NAME'), 'admin.eznewlife.com') !== false) {
            $this->redirectPath = '/admin/articles/datatables';
        } else {
            $this->redirectPath = '/';
        }

        $this->middleware('guest', ['except' => ['getLogout', 'avbodyLogout', 'enlLogout','redirectToEnl','redirectToProvider','handleEnlCallback']]);


    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        if (strpos(URL::previous(), 'avbody.info') === false) {
            $referrer = "http://avbody.info";
        } else {
            $referrer = URL::previous();;
        }
        /*
         * http://stackoverflow.com/questions/30660847/laravel-socialite-invalidstateexception
         */
        return Socialite::driver('facebook')
            ->with(['state' => $referrer])
            ->redirect();
    }

    public function handleProviderCallback()
    {
        if (Request::Input('error') == 'access_denied') {
            return redirect('/');
        }
        try {
            Socialite::driver('facebook')->stateless();
            $user = Socialite::driver('facebook')->user();
        } catch (Exception $e) {
            return redirect('auth/facebook');
        }

        $authUser = $this->findOrCreateUser($user);
        $av_user_id=$authUser->id;
      //dd(  $av_user_id);
        if ($authUser) {
            /*
            $av=new AvUser();
            $av->setConnection('master');
            $av=$av->where('id', $av_user_id)->first();

            $av->login_counts=$authUser->login_counts+1;
            $av->save();*/
            Auth::av_user()->attempt(['name' => $authUser->name]);
            Auth::av_user()->login($authUser, true);
        //    $file = '/home/eznewlife/ad.eznewlife.com/laravel/people.txt';
// Ouvre un fichier pour lire un contenu existant
            //$current = file_get_contents($file);
// Ajoute une personne
            //$current .= date("Y-m-d H:i:s")."\n";
// Écrit le résultat dans le fichier
           // file_put_contents($file, $current);
         }

        return redirect(Request::Input('state'));
    }

    public function redirectToEnl()
    {
        if (strpos(URL::previous(), 'eznewlife.com') === false) {
            $referrer = "http://eznewlife.com";
        } else {
            $referrer = URL::previous();;
        }
        /*
         * http://stackoverflow.com/questions/30660847/laravel-socialite-invalidstateexception
         */
        // dd(Socialite::driver('enlfacebook'));
        return Socialite::driver('enlfacebook')
            ->with(['state' => $referrer])
            ->redirect();
    }

    public function handleEnlCallback()
    {
        if (Request::Input('error') == 'access_denied') {
            return redirect('/');
        }
        try {
            Socialite::driver('enlfacebook')->stateless();
            $user = Socialite::driver('enlfacebook')->user();
        } catch (Exception $e) {
            return redirect('auth/facebook');
        }

        $authUser = $this->enlFindOrCreateUser($user);
        Auth::enl_user()->attempt(['name' => $authUser->name]);
        Auth::enl_user()->login($authUser, true);
        if (empty(Request::Input('state')))  return redirect('/');

        return redirect(Request::Input('state'));
    }

    private function enlFindOrCreateUser($facebookUser)
    {
        $authUser = EnlUser::where('facebook_id', $facebookUser->id)->where('facebook_id', '!=', 0)->first();
        /*  $mailUser = AvUser::where('email', $facebookUser->email)->first();
          //修改
          if ($mailUser) {
              $mailUser->nick_name = $facebookUser->name;
              $mailUser->facebook_id = $facebookUser->id;
              $mailUser->avatar = $facebookUser->avatar;
              $mailUser->save();
              return $mailUser;
          }*/
        if ($authUser) {
            return $authUser;
        }
        $enl_user = new EnlUser();
        $enl_user->setConnection('master');
        $enl_user->name = $facebookUser->id;
        $enl_user->nick_name = $facebookUser->name;
        $enl_user->email = $facebookUser->email;
        $enl_user->facebook_id = $facebookUser->id;
        $enl_user->avatar = $facebookUser->avatar;
        $enl_user->save();
        return $enl_user;
    }

    public function enlLogin()
    {
        return view('enl.login')
            ->with('plan', 1)
            ->with('mobile', 0);
    }

    public function enlLogout()
    {
        // Auth::setConnection('master');
        // Auth::av_user()->changeConnection('master');
        Auth::enl_user()->logout();
        //Auth::logout();
        return redirect()->back();
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $facebookUser
     * @return User
     */
    private function findOrCreateUser($facebookUser)
    {
        $authUser = AvUser::where('facebook_id', $facebookUser->id)->where('facebook_id', '!=', 0)->first();
        /*  $mailUser = AvUser::where('email', $facebookUser->email)->first();
          //修改
          if ($mailUser) {
              $mailUser->nick_name = $facebookUser->name;
              $mailUser->facebook_id = $facebookUser->id;
              $mailUser->avatar = $facebookUser->avatar;
              $mailUser->save();
              return $mailUser;
          }*/
        if ($authUser) {
            return $authUser;
        }
        $av_user=new AvUser();
        $av_user->setConnection('master');
        $av_user->name = $facebookUser->id;
        $av_user->login_date=date("Y-m-d");
        $av_user->nick_name = $facebookUser->name;
        $av_user->email = $facebookUser->email;
        $av_user->facebook_id = $facebookUser->id;
        $av_user->avatar = $facebookUser->avatar;
        $av_user->save();
      //  $av_user->firstOrCreate($data);
        return $av_user;
    }

    public function avbodyLogin()
    {
        return view('avbodies.login')
            ->with('plan', 2)
            ->with('mobile', 0);
    }

    public function avbodyLogout()
    {
		
		//Config::set('database.default', 'master');
        //AvUser::changeConnection('master');
		//\Illuminate\Contracts\Auth\Guard::logout();
        // Auth::av_user()->changeConnection('master');
        Auth::av_user()->logout();
		//AvUser::on('master')->update(['remember_token'])
        //Auth::logout();
        return redirect()->back();
    }
}
