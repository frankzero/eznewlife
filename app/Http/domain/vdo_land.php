<?php

Route::group(['domain' => $_domain('vdo.land')], function () {

    /**fb lives video id */
    Route::get('/fb_lives/{id}', ['uses' => 'FbLiveController@show', 'as' => 'fb_lives.show']);
    Route::get('/404notfound', ['uses' => 'FbLiveController@notfound', 'as' => 'fb_lives.notfound']);
});