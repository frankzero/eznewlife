<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    //

    protected $connection = 'mysql2';
    protected $table = 'email';
    public $timestamps = false;
}
