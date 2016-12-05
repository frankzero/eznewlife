<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AvUserRegularLog extends Model
{
    //
    protected $table = 'av_users_regular_logs';
    protected $connection = 'mysql';
    protected $fillable=[  'visit', 'type', 'record_date'];
}
