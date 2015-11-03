# laravel-wechat
之前写的一个适用于Laravel的微信开发框架

## Usage
执行`php artisan wechat:init`命令后会创建`app/wechat.php`文件，用于注册处理请求的handlers。
继承\Linroid\Wechat\WechatHandler,然后会有一些很方便的方法用来处理请求。

## Sample

```php
<?php
use Linroid\Wechat\WechatHandler;
use Linroid\Wechat\Msg\AbstractMsg;
use Linroid\Wechat\Response\TextResponse;

/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 2/4/15
 * Time: 6:17 PM
 */

class SubscribeHandler extends WechatHandler {

    public function onMatch(AbstractMsg $msg)
    {
        return $msg->isEvent('subscribe');
    }

    public function onResponse(AbstractMsg $msg)
    {
        $user = $msg->user;
        $api = \Linroid\Wechat\WechatApi::create();
        $userInfo = $api->getUserInfo($msg->fromUsername);
        $user = $user->fill($userInfo);
        $user->subscribe = 1;
        $user->save();

        $response = new TextResponse($msg);
        $content = "欢迎关注%s";
        $response->setContent(sprintf($content, Config::get('wechat::nickname')));

        return $response;
    }
}
