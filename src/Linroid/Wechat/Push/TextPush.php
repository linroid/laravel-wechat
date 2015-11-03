<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-7-31
 * Time: 下午1:58
 */

namespace Linroid\Wechat\Push;


class TextPush extends AbstractPush{
    public $text;
    public $msgtype = 'text';
    /**
     * @param string $content
     */
    public function setContent($content){
        $this->text = ['content'=>$content];
    }
}