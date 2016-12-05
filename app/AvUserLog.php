<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AvUserLog extends Model
{
    //
    protected $table = 'av_users_logs';
    protected $connection = 'mysql';
    protected $fillable=[ 'av_user_id', 'session', 'refer', 'url', 'ip', 'agent','login_counts','login_date','record_date'];
}
