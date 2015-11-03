<?php
use Linroid\Wechat\WechatUser;

/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 2/4/15
 * Time: 10:43 PM
 * @param WechatUser $wechatUser
 * @param $path
 * @param array $path_params
 * @return string
 */
function wechat_url(WechatUser $wechatUser, $path, $path_params=array()){
    $path_params = array('token'=>$wechatUser->token, 'path'=>query_url($path, $path_params));
    return  query_url('wechat/redirect', $path_params);
}
/**
 * 生成带参数的URL
 * @param $path
 * @param $params
 * @return string
 */

function query_url($path, $params){
    $url = url($path);
    $queries = http_build_query($params);
    return $url.'?'.$queries;
}