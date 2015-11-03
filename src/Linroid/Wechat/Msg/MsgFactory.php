<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-5-25
 * Time: 上午6:39
 */

namespace Linroid\Wechat\Msg;


use Linroid\Wechat\WechatUser;

class MsgFactory {
    /**
     * 从微信传过来的数据中判断消息类型,返回对应的消息实例
     * @param string $postData
     * @return TextMsg|VoiceMsg
     */
    public static function fromData($postData){
        $postObj = simplexml_load_string($postData, 'SimpleXMLElement', LIBXML_NOCDATA);
//        $dom = new \DOMDocument('1.0', 'utf-8');
//        $dom->loadXML($postData);
        $msgType = $postObj->MsgType;
        switch($msgType){
            case 'voice':
                $msg = new VoiceMsg();
                break;
            case 'text':
                $msg = new TextMsg();
                break;
            case 'event':
                $msg = new EventMsg();
                break;
            default:
                $msg = new TextMsg();
                break;
        }
        $msg->parseXML($postObj);
        $msg->user = WechatUser::findOrCreate($msg->fromUsername);
        return $msg;
    }
} 