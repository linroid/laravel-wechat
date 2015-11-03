<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 2/4/15
 * Time: 6:44 PM
 */

namespace Linroid\Wechat;


use Auth;
use Config;
use Session;

class Wechat {
    /**
     * @var array  $handlers
     */
    public static $handlers = array();
    private static $wechat_user;

    public static function add(WechatHandler $handler){
        array_push(self::$handlers, $handler);
    }
    public static function getSessionOpenid(){
        return Session::get('wechat_openid_'.Config::get('wechat::id'));
    }
    public static function setSessionOpenid($openid){
        Session::put('wechat_openid_'.Config::get('wechat::id'), $openid);
    }
    /**
     * 验证消息的合法性
     * @param $signature
     * @param $timestamp
     * @param $nonce
     * @return bool
     */
    public static function checkSignature($signature, $timestamp, $nonce)
    {
        $token = Config::get('wechat::token');
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return bool
     */
    public static function guest()
    {
        return is_null(static::user());
    }

    /**
     * @return WechatUser
     */
    public static function user(){
        if(!static::$wechat_user){
            $openid = self::getSessionOpenid();
            if(!empty($openid)){
                static::$wechat_user = WechatUser::findOrCreate($openid);
            }
        }
        return static::$wechat_user;
    }
}