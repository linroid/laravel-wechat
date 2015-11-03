<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-5-24
 * Time: 下午7:15
 */

namespace Linroid\Wechat\Response;


use SimpleXMLElement;
use Linroid\Wechat\Msg\AbstractMsg;
use View;

abstract class AbstractResponse {
    public $fromUsername;
    public $toUsername;
    public $createTime;
    public $msgType;
    public $msgId;
    /**
     * @var \DOMDocument
     */
    protected $response;

    function __construct(AbstractMsg $msg)
    {
        $this->readMsg($msg);
    }

    private function readMsg(AbstractMsg $msg){
        $this->fromUsername = $msg->toUsername;
        $this->toUsername   = $msg->fromUsername;
        $this->createTime  = time();
        $this->msgId       = $msg->msgId;
    }
//
//    /**
//     * @return string
//     */
//    public function create(){
//        $response = new \DOMDocument('1.0', 'utf-8');
//        $toUserName = $response->createElement("ToUserName", $this->toUsername);
//        $fromUsername   = $response->createElement("FromUserName", $this->fromUsername);
//        $createTime     = $response->createElement("CreateTime", $this->createTime);
//        $msgType        = $response->createElement("MsgType", $this->msgType);
//        $response->appendChild($toUserName);
//        $response->appendChild($fromUsername);
//        $response->appendChild($createTime);
//        $response->appendChild($msgType);
//        return $this->addContent($response)->saveXML();
//
//    }

    function __toString()
    {
        return View::make('wechat::response.'.$this->msgType)
            ->with('response', $this)->__toString();
    }

//
//    /**
//     * @param \DOMDocument $response
//     * @return \DOMDocument
//     */
//    abstract function addContent(\DOMDocument $response);
//    abstract function getView();
}