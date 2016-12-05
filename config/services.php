<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'facebook' => [
        'client_id' => env('AV_FACEBOOK_CLIENT_ID','508450452694535'),
        'client_secret' => env('AV_FACEBOOK_CLIENT_SECRET','1c12327c8e43cd84cf4c981c4cdafbf2'),
        'redirect' => env('AV_CALLBACK_URL','http://avbody.info/auth/facebook/callback'),
    ],
    'enlfacebook' => [
        'client_id' => env('ENL_FACEBOOK_CLIENT_ID','1001677996555993'),
        'client_secret' => env('ENL_FACEBOOK_CLIENT_SECRET','4211dd2d0eec5dd87f061e4d96e18971'),
        'redirect' => env('ENL_CALLBACK_URL','http://eznewlife.com/auth/facebook/callback'),
    ],

];
