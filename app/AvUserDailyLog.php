<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AvUserDailyLog extends Model
{
    //
    protected $table = 'av_users_daily_logs';
    protected $connection = 'mysql';
    protected $fillable=[ 'fresh', 'visit', 'refer_ad','refer_google','refer_facebook','refer_avbody','refer_other', 'record_date'];
}
