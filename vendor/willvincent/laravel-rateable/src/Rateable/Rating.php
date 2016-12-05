<?php namespace willvincent\Rateable;

use Config;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['rating'];
    //protected $connection = 'master';
    /**
     * @return mixed
     */
    public function rateable()
    {
        return $this->morphTo();
    }

    /**
     * Rating belongs to a user.
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(Config::get('auth.model'));
    }
}
