<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-5-24
 * Time: 下午7:17
 */

namespace Linroid\Wechat\Response;


class MusicResponse extends AbstractMediaResponse{
    protected $msgType = 'music';

    function uploadMedia($media)
    {
        // TODO: Implement uploadMedia() method.
    }


    function addContent(\DOMDocument $response)
    {
        // TODO: Implement addContent() method.
    }
}