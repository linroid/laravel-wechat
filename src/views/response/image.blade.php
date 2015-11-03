@extends('wechat::response.base')
@section('content')
<Image>
    <MediaId><![CDATA[{{$response->mediaId }}]]></MediaId>
</Image>
@stop