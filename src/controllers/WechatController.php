<?php
namespace Linroid\Wechat;
use App;
use BaseController;
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 2/4/15
 * Time: 5:14 PM
 */

use Config;
use File;
use Input;
use Linroid\Wechat\Msg\AbstractMsg;
use Linroid\Wechat\Msg\MsgFactory;
use Httpful\Request;
use Linroid\Wechat\Push\TextPush;
use Linroid\Wechat\Response\AbstractResponse;
use Linroid\Wechat\Response\TextResponse;
use Redirect;
use Response;
use Route;

class WechatController extends BaseController
    implements WechatMsgListener{
    /**
     * @return Response 响应微信服务器发送的消息的action
     */
    public function run()
    {

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        if(!Wechat::checkSignature($signature, $timestamp, $nonce)){
            App::abort(400);
        }
        if(Input::has('echostr')){
            echo Input::get('echostr');
            exit;
        }
        $data       = $GLOBALS["HTTP_RAW_POST_DATA"];
        $msg        = MsgFactory::fromData($data);
        $this->onStart($msg);

        if(File::exists(app_path().'/wechat.php')){
            require_once app_path().'/wechat.php';
        }
        $handlers = Wechat::$handlers;
        foreach($handlers as $handler){
            /**
             * @var WechatHandler $handler
             */
            if($handler->onMatch($msg)){
                $response = $handler->onResponse($msg);
                break;
            }
        }
        if(empty($response)){
            $response = new TextResponse($msg);
            $content = <<<CONTENT
[发红包啦]谢谢您的参与,
<a href="%s">点击领取</a>
CONTENT;
            $response->setContent(sprintf($content, wechat_url($msg->user, 'red_pack')));

        }
        $this->onPostResponse($msg, $response);
        if($msg->fromUsername == Config::get('wechat::debug_openid')){
            $push = new TextPush($msg->user->openid);
            $push->setContent($data."\n\n".$response->__toString());
            $api = WechatApi::create();
            $api->sendPush($push);
        }
        return Response::make($response);
    }

    /**
     * 跳转，防止用户分享token
     */
    public function redirect(){
        $token = Input::get('token');
        $path = Input::get('path');
        $wechatUser = WechatUser::whereToken($token)->first();
        if(empty($wechatUser)){
            App::abort(400);
        }
        Wechat::setSessionOpenid($wechatUser->openid);
        return Redirect::to($path);
    }
    public function avatar(){
        $openid = Route::input('openid');
        /**
         * @var WechatUser $wechatUser
         */
        $wechatUser = WechatUser::whereOpenId($openid)->first();
        if(!$wechatUser || empty($wechatUser->headimgurl)){
            App::abort(404);
        }
        $response = Request::get($wechatUser->headimgurl)->send();
//        $wechat_avatar = file_get_contents($user->headimgurl);
        header('Content-Type:image/jpeg');
        echo ($response->body);
    }
    /**
     * OAuth回调
     */
    public function callback()
    {
        $code  = Input::get('code');
        $state = Input::get('state');
        $wechatOAuth2 = WechatOAuth2::create();
        $tokenResponse = $wechatOAuth2->getTokenByCode($code);
        if(!$tokenResponse){
            App::abort(400);
        }
        $userInfo = $wechatOAuth2->getUserInfo(
            $tokenResponse['access_token'],
            $tokenResponse['openid']
        );
        if($userInfo){
            /**
             * @var WechatUser $wechatUser
             */
            $wechatUser = WechatUser::find($userInfo['openid']);
            if(!$wechatUser){
                $wechatUser = new WechatUser($userInfo);
                $wechatUser->token = str_random(48);
            }else{
                $wechatUser->fill($userInfo);
            }
            $wechatUser->save();
            Wechat::setSessionOpenid($wechatUser->openid);
            return Redirect::to($state);
        }else{
            App::abort(500);
        }
    }

    /**
     * 在刚处理消息之前的回调方法
     * @param Msg\AbstractMsg $msg
     * @return mixed
     */
    function onStart(AbstractMsg &$msg)
    {
    }

    /**
     * 当响应信息创建完成后回调该方法,在该回调方法中可以对已经创建好的响应做一些处理,如在末尾添加未读消息,将响应消息添加到数据库中,在微信重试请求时直接响应;
     * @param Msg\AbstractMsg $msg
     * @param AbstractResponse $response
     * @return void
     */
    function onPostResponse(&$msg, &$response)
    {
        // TODO: Implement onPostResponse() method.
    }
}
