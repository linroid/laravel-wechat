<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-5-25
 * Time: 上午6:26
 */

namespace Linroid\Wechat\Msg;


use Linroid\Wechat\WechatMsgListener;
use SimpleXMLElement;
class TextMsg extends AbstractMsg{
    public $content;
    /**
     * @param \SimpleXMLElement $xml
     * @return AbstractMsg
     */
    function parseXML(SimpleXMLElement $xml)
    {
        parent::parseXML($xml);
        $this->content = $xml->Content;
    }

}