<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 2/4/15
 * Time: 6:16 PM
 */

namespace Linroid\Wechat;


use Linroid\Wechat\Msg\AbstractMsg;

abstract class WechatHandler {
    public abstract function onMatch(AbstractMsg $msg);
    public abstract function onResponse(AbstractMsg $msg);
}