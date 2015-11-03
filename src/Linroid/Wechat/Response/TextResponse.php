<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-5-24
 * Time: 下午7:17
 */

namespace Linroid\Wechat\Response;


class TextResponse extends AbstractResponse {
    public $msgType = 'text';
    public $content;
    public function setContent($content){
        $this->content = $content;
    }
//    function addContent(\DOMDocument $response)
//    {
//        $content = $response->createElement("Content", $this->content);
//        $response->appendChild($content);
//        return $response;
//    }
}