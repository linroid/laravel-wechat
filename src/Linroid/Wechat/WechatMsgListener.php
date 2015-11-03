<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-5-25
 * Time: 上午6:42
 */

namespace Linroid\Wechat;


use Linroid\Wechat\Msg\AbstractMsg;
use Linroid\Wechat\Msg\EventMsg;
use Linroid\Wechat\Response\AbstractResponse;
use Response;
use Linroid\Wechat\Msg\TextMsg;
use Linroid\Wechat\Msg\VoiceMsg;

interface WechatMsgListener {

    /**
     * 在刚处理消息之前的回调方法
     * @param Msg\AbstractMsg $msg
     * @return mixed
     */
    function onStart(AbstractMsg &$msg);

    /**
     * 当响应信息创建完成后回调该方法,在该回调方法中可以对已经创建好的响应做一些处理,如在末尾添加未读消息,将响应消息添加到数据库中,在微信重试请求时直接响应;
     * @param Msg\AbstractMsg $msg
     * @param AbstractResponse $response
     * @return
     */
    function onPostResponse(&$msg, &$response);
}