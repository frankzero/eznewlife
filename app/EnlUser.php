<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Auth\Passwords\CanResetPassword;
use Kbwebs\MultiAuth\PasswordResets\CanResetPassword;
use Kbwebs\MultiAuth\PasswordResets\Contracts\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
//use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use App\Article;


use App\UserCollect;


class EnlUser extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'enl_users';
	protected $connection = 'slave';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','nick_name', 'email', 'password','facebook_id','avatar'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $conection = 'mysql';
    protected $hidden = ['password', 'remember_token'];
    /**
     * 作者發表的文章
     * */
    public function changeConnection($conn)
    {
        $this->connection = $conn;
    }
    public function articles()
    {
        return $this->hasMany('App\Article','created_user','id');
    }
    public function articlesCount()
    {
        return $this->articles()
            ->selectRaw('created_user, count(*) as aggregate')
            ->groupBy('created_user');
    }
    /*
    public function collects()
    {
        return DB::table('users')->leftJoin('users_collects', 'users.id', '=', 'users_collects.user_id')->join('articles', 'articles.id', '=', 'users_collects.article_id')
            ->select('articles.title', 'articles.id');
    }
*/
    public function collects()
    {

        return $this->hasMany('App\EnlUserCollect');
    }


    /*動圖
     * */
    public function animations()
    {
        return $this->hasMany('App\Animation','created_user','id');
    }
    public function animationsCount()
    {
        return $this->animations()
            ->selectRaw('created_user, count(*) as aggregate')
            ->groupBy('created_user');
    }

    public function updater_articles()
    {   //return $this->hasOne('Phone', 'foreign_key', 'local_key');
        //return $this->hasOne('NationRegion','id','nation_region_id');
        return $this->hasMany('App\Article','updated_user','id');
    }
    public function updaterArticlesCount()
    {   //return $this->hasOne('Phone', 'foreign_key', 'local_key');
        //return $this->hasOne('NationRegion','id','nation_region_id');
        return $this->updater_articles()
            ->selectRaw('updated_user, count(*) as aggregate')
            ->groupBy('updated_user');
    }


    public function updater_animations()
    {   //return $this->hasOne('Phone', 'foreign_key', 'local_key');
        //return $this->hasOne('NationRegion','id','nation_region_id');
        return $this->hasMany('App\Animation','updated_user','id');
    }
    public function updaterAnimationsCount()
    {   //return $this->hasOne('Phone', 'foreign_key', 'local_key');
        //return $this->hasOne('NationRegion','id','nation_region_id');
        return $this->updater_animations()
            ->selectRaw('updated_user, count(*) as aggregate')
            ->groupBy('updated_user');
    }
}
