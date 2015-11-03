<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-5-24
 * Time: 下午7:16
 */

namespace Linroid\Wechat\Response;


use Linroid\Wechat\WechatApi;

class ImageResponse extends AbstractMediaResponse{
    public $msgType = 'image';
    function uploadMedia($media)
    {
        $api = WechatApi::create();
        $api->uploadMedia('image', 'sf');
    }
    public function setMedia($mediaId){
        $this->mediaId = $mediaId;
    }
}