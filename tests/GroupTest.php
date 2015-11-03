<?php
use Linroid\Wechat\WechatApi;

/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-7-26
 * Time: 下午4:27
 */

class GroupTest extends PHPUnit_Framework_TestCase{
    /**
     *  @var WechatApi
     */
    private $wechat;
    public function setUp(){
        parent::setUp();
        $this->wechat = WechatApi::create();
    }
    public function testGetGroups(){
        $r = $this->wechat->getGroups();
        print_r($r);
    }
}