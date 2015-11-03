<xml>
    <ToUserName><![CDATA[{{$response->toUsername}}]]></ToUserName>
    <FromUserName><![CDATA[{{$response->fromUsername}}]]></FromUserName>
    <CreateTime>{{$response->createTime}}</CreateTime>
    <MsgType><![CDATA[{{ $response->msgType }}]]></MsgType>
    @yield('content')
</xml>