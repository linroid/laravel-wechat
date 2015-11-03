<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 2/4/15
 * Time: 11:27 PM
 */

use Linroid\Wechat\Wechat;
use Linroid\Wechat\WechatOAuth2;

if(App::isLocal()){
    Wechat::setSessionOpenid(Config::get('wechat::debug_openid'));
}
Route::filter('wechat', function(){
    if(Wechat::guest()){
        $oauth2 = WechatOAuth2::create();
        return $oauth2->authorize(Request::path());
    }
});