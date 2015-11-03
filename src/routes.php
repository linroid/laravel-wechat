<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 2/4/15
 * Time: 11:27 PM
 */

Route::any('/wechat', 'Linroid\\Wechat\\WechatController@run');
Route::get('/wechat/callback', 'Linroid\\Wechat\\WechatController@callback');
Route::get('/wechat/redirect', 'Linroid\\Wechat\\WechatController@redirect');