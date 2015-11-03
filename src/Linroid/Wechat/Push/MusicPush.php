<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-7-31
 * Time: 下午2:01
 */

namespace Linroid\Wechat\Push;


class MusicPush extends AbstractPush{
    public $msgtype = 'music';
    public $music;
    public function setMusic($title, $description, $musicurl, $hqmusicurl, $thumb_media_id){
        $this->music = array(
            'title'             =>  $title,
            'description'       =>  $description,
            'musicurl'          =>  $musicurl,
            'hqmusicurl'        =>  $hqmusicurl,
            'thumb_media_id'    =>  $thumb_media_id
        );
    }
}