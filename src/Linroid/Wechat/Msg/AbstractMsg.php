<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-5-25
 * Time: 上午6:23
 */

namespace Linroid\Wechat\Msg;


use DateTime;
use Doctrine\Common\Annotations\Annotation\Enum;
use Linroid\Wechat\WechatApi;
use Linroid\Wechat\WechatUser;
use SimpleXMLElement;
use Linroid\Wechat\Response\AbstractResponse;
use Linroid\Wechat\WechatMsgListener;

abstract class AbstractMsg {
    public $fromUsername;
    public $toUsername;
    public $createTime;
    public $msgType;
    public $msgId;
    /**
     * @var WechatUser
     */
    public $user;

    /**
     * @param \SimpleXMLElement $xml
     * @return AbstractMsg
     */
    public function parseXML(SimpleXMLElement $xml){
        $this->fromUsername = $xml->FromUserName->__toString();
        $this->toUsername   = $xml->ToUserName->__toString();
        $this->msgType      = $xml->MsgType->__toString();
        $this->createTime   = $xml->CreateTime->__toString();
        $this->msgId        = $xml->MsgId->__toString();

    }
    public function isText(){
        return $this->msgType == 'text';
    }
    public function isVoice(){
        return $this->msgType == 'voice';
    }
    public function matchText($pattern){
        /**
         * @var TextMsg $this
         */
        return $this->isText() && preg_match($pattern, $this->content);
    }
    public function equalText($text){
        /**
         * @var TextMsg $this
         */
        return $this->isText() && $text==$this->content;
    }

    /**
     * @param enum(VIEW,CLICK,VIEW,SCAN,LOCATION,subscribe,unsubscribe)  $event 事件类型
     * @return bool
     */
    public function isEvent($event){
        /**
         * @var EventMsg $this
         */
        return $this->msgType=='event' && $this->event==$event;
    }
    public function matchEventKey($pattern){
        /**
         * @var EventMsg $this
         */
        return $this->isEvent('CLICK') && preg_match($pattern, $this->eventKey);
    }
    public function equalEventKey($eventKey){
        /**
         * @var EventMsg $this
         */
        return $this->isEvent('CLICK') && ($eventKey == $this->eventKey);
    }
}