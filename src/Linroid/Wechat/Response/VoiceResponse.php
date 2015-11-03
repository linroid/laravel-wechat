<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-5-24
 * Time: 下午7:31
 */

namespace Linroid\Wechat\Response;


class VoiceResponse extends AbstractMediaResponse{
    protected $msgType = 'voice';

    function uploadMedia($media)
    {
        // TODO: Implement uploadMedia() method.
    }
    function addContent(\DOMDocument $response)
    {
        // TODO: Implement addContent() method.
    }
}