<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-5-25
 * Time: 上午6:58
 */

namespace Linroid\Wechat\Msg;


use Event;
use SimpleXMLElement;
use Linroid\Wechat\WechatMsgListener;

/**
 * Class EventMsg
 * @package Linroid\Wechat\Msg
 * @property string $eventKey
 */
class EventMsg extends AbstractMsg{
    public $event;
    private $xml;
    /**
     * @param \SimpleXMLElement $xml
     * @return AbstractMsg
     */
    public function parseXML(SimpleXMLElement $xml)
    {
        parent::parseXML($xml);
        $this->event = $xml->Event->__toString();
        $this->xml = $xml;
    }
    public function __get($name)
    {
        $key = studly_case($name);
        if(isset($this->xml->$key)){
            return $this->xml->$key->__toString();
        }
    }
}