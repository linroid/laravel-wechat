<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-5-24
 * Time: 下午7:32
 */

namespace Linroid\Wechat\Response;


abstract class AbstractMediaResponse extends AbstractResponse{
    protected  $mediaId;
    abstract function uploadMedia($media);
}