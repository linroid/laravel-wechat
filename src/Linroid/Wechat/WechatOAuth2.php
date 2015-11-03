<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-7-26
 * Time: 下午4:58
 */

namespace Linroid\Wechat;

use Httpful\Request;
use Config;
use Illuminate\Support\Facades\Redirect;
use Response;

class WechatOAuth2 {
    public $appId;
    public $secret;

    function __construct($appId, $secret)
    {
        $this->appId = $appId;
        $this->secret = $secret;
    }
    public static function create(){
        $appId = Config::get('wechat::appid');
        $secret = Config::get('wechat::secret');
        return new WechatOAuth2($appId, $secret);
    }


    /**
     * 微信授权，获取用户信息
     * @param $path
     * @param bool $showAuthorize 是否显示授权页面(不显示的话只能获得用户openid)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authorize($path, $showAuthorize=true){
        $baseUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?';
        /**
         * state 为重定向后会带上state参数，开发者可以填写a-zA-Z0-9的参数值,这里用于授权之后用于跳转.
         */
        $params = array(
            'appid' => $this->appId,
            'redirect_uri' => url('wechat/callback'),
            'response_type' => 'code',
            'scope' => $showAuthorize ? 'snsapi_userinfo' : 'snsapi_base',
            'state' => urlencode($path)
        );
        $url = $baseUrl.http_build_query($params).'#wechat_redirect';
        return \Redirect::away($url);
    }

    /**
     * 通过code换取网页授权access_token
     * @param string $code
     * @return array|bool
     */
    public function getTokenByCode($code){
        $baseUrl = "https://api.weixin.qq.com/sns/oauth2/access_token?";
        $params = array(
            'appid'=>$this->appId,
            'secret'=>$this->secret,
            'code' => $code,
            'grant_type' => 'authorization_code'
        );
        $url = $baseUrl.http_build_query($params);
        $response = Request::get($url)->send();
        return WechatApi::assertError($response);
    }

    /**
     * 刷新access_token
     * @param string $access_token
     * @return array|bool
     */
    public function refreshToken($access_token){
        $baseUrl = "https://api.weixin.qq.com/sns/oauth2/refresh_token?";
        $params = array(
            'appid'         =>  $this->appId,
            'refresh_token' =>  $access_token,
            'grant_type'    =>  'refresh_token'
        );
        $url = $baseUrl.http_build_query($params);
        $response = Request::get($url)->send();
        return WechatApi::assertError($response);
    }
    public function getUserInfo($access_token, $openid, $lang='zh_CN'){
        $baseUrl = 'https://api.weixin.qq.com/sns/userinfo?';
        $params = array(
            'openid'=>$openid,
            'access_token' => $access_token,
            'lang' => $lang
        );
        $url = $baseUrl.http_build_query($params);
        $response = Request::get($url)->send();
        return WechatApi::assertError($response);
    }
    public function isTokenValid($access_token, $openid){
        $baseUrl = 'https://api.weixin.qq.com/sns/auth?';
        $params = array(
            'access_token'=>$access_token,
            'openid'=>$openid
        );
        $url = $baseUrl.http_build_query($params);
        $response = Request::get($url)->send();
        return WechatApi::assertError($response);
    }
} 