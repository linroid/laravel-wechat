<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-5-24
 * Time: 上午8:48
 */

namespace Linroid\Wechat;

use Cache;
use Config;
use Httpful\Handlers\JsonHandler;
use Httpful\Httpful;
use Httpful\Mime;
use Httpful\Request;
use Linroid\Wechat\Push\AbstractPush;

class WechatApi {
    const TOKEN_CACHE_KEY = 'wechat_access_token';
    const APP_ID_CACHE_KEY = 'wechat_appid';
    const SECRET_CACHE_KEY = 'wechat_secret';
    const BASE_URL = 'https://api.weixin.qq.com/cgi-bin/';

    public $access_token;
    public $appId;
    public $secret;

    private function __construct($appId, $secret, $access_token)
    {
        $this->appId = $appId;
        $this->secret = $secret;
        $this->access_token = $access_token;
    }


    /**
     * 创建实例
     * @return WechatApi
     */
    public static function create(){
        $appId = Config::get('wechat::appid');
        $secret = Config::get('wechat::secret');
        Httpful::register(Mime::JSON, new JsonHandler(array('decode_as_array' => true)));
        $access_token = self::readToken($appId, $secret);
        return new WechatApi($appId, $secret, $access_token);
    }

    /**
     * 获取用户详细信息
     * @param $openid
     * @param string $lang
     * @return array|bool
     */
    public function getUserInfo($openid, $lang='zh_CN'){
        $url = $this->buildUrl('user/info', ['openid'=>$openid, 'lang'=>$lang]);
        return $this->get($url);
    }
    /**
     * 获取用户列表
     * @param string $nextOpenid
     * @return array|bool
     */
    public function getUsers($nextOpenid = ''){
        $url = $this->buildUrl('user/get', ['next_openid'=>$nextOpenid]);
        return $this->get($url);
    }
    /**
     * 创建分组
     * @param $name
     * @return array|bool
     */
    public function createGroup($name){
        $url = $this->buildUrl('groups/create');
        $data = array(
            'group' => array(
                'name'=>$name
            )
        );

        return $this->post($url, $data);
    }

    /**
     * 获取用户分组列表
     * @return array|bool
     */
    public function getGroups(){
        $url = $this->buildUrl('groups/get');
        return $this->get($url);
    }

    public function getUserGroupId($openId){
        $url = $this->buildUrl('groups/getid');
        $data = ['openid'=>$openId];

        return $this->post($url, $data);
    }

    public function getMenu()
    {
        $url = $this->buildUrl('menu/get');
        $data =  $this->get($url);
        return $data ? $data->menu : $data;
    }

    /**
     * @param $scene_id
     * @param int $type 0为临时,1为永久
     * @param int $expire_seconds
     * @return array|bool
     */
    public function createQrcode($scene_id, $type=0, $expire_seconds=1800){
        $url = $this->buildUrl('qrcode/create');
        $postData = array(
            'action_name'   => $type ? 'QR_LIMIT_SCENE' : 'QR_SCENE',
            'action_info'   => array(
                'scene'=>array(
                    'scene_id'=>$scene_id,
                )
            )
        );
        if(!$type){
            $postData['expire_seconds'] = $expire_seconds;
        }
        return $this->post($url, $postData);
    }
    public function getQrcodeUrl($ticket){
        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
        return $url;
    }


    public function getToken(){
        return $this->access_token;
    }

    /**
     * 设置菜单
     * @param $menu
     * @return bool
     */
    public function setMenu($menu)
    {

        $url = $this->buildUrl('menu/create');
        $response = Request::post($url)
            ->body($menu)
            ->sendsJson()
            ->send();
        return self::assertError($response);
    }
    public function uploadMedia($type, $media){
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token={$this->access_token}&type={$type}";
        Request::post($url, null, Mime::FORM);
    }
    public function sendPush(AbstractPush $push){
        $url = $this->buildUrl('message/custom/send');
        $response = Request::post($url, $push->toJson())->send();
        return self::assertError($response);
    }
    private function get($url){
        $response = Request::get($url)
            ->expectsType('json')
            ->send();
        return self::assertError($response);
    }
    private function post($url, $data=array()){
        $response = Request::post($url, json_encode($data, JSON_UNESCAPED_UNICODE))
            ->send();
        return self::assertError($response);
    }

    /**
     * @param string $path
     * @param array $params
     * @return string
     */
    private function buildUrl($path, $params = array()){
        $params['access_token'] = $this->access_token;
        $url = self::BASE_URL . $path . '?' . http_build_query($params);
        return $url;
    }
    /**
     * 从缓存中读取access_token,如果没有则创建
     * @param $appId
     * @param $secret
     * @return mixed
     */
    private static function readToken($appId, $secret)
    {
        $access_token_cache_key = $appId.'_'.self::TOKEN_CACHE_KEY;
        $access_token = Cache::get($access_token_cache_key);
        if(empty($access_token)){
            $data = self::createToken($appId, $secret);
            if(!empty($data['access_token'])){
                Cache::put($access_token_cache_key, $data['access_token'], $data['expires_in'] /60 - 1);
                $access_token = $data['access_token'];
            }
        }
        return $access_token;
    }

    private static function createToken($appId, $secret)
    {
        $params = array(
            'grant_type' => 'client_credential',
            'appid'      => $appId,
            'secret'     => $secret
        );
        $url = 'https://api.weixin.qq.com/cgi-bin/token?'.http_build_query($params);
        $response = Request::get($url)->send();
        return self::assertError($response);
    }
//    //通过code换取网页授权access_token
//    public static function getTokenByCode($code){
//        $appId = Config::get('wechat::appid');
//        $secret = Config::get('wechat::secret');
//        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appId}&secret={$secret}&code={$code}&grant_type=authorization_code";
//        $response = Request::get($url)->expects('application/json')->send();
//        return self::assertError($response);
//    }

    /**
     * @param $response
     * @return array|bool
     */
    public static function assertError($response){
        $data = $response->body;
        if(is_string($data)){
            $data = json_decode($data, true);
        }
//        print_r($data);
        if(!isset($data['errcode'])  || ['errcode']){
            return $data;
        }else{
            return false;
        }
    }
}