<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-7-31
 * Time: 下午2:02
 */

namespace Linroid\Wechat\Push;


class AbstractPush {
    public $touser;
    public $msgtype;
    function __construct($touser)
    {
        $this->touser = $touser;
    }
    public function toJson(){
        return json_encode($this, JSON_UNESCAPED_UNICODE);
    }
}