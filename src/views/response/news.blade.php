@extends('wechat::response.base')
@section('content')
<ArticleCount>{{ $response->getCount() }}</ArticleCount>
<Articles>
    @foreach($response->articles as $article)
    <item>
        <Title><![CDATA[{{$article->title}}]]></Title>
        <Description><![CDATA[{{$article->description}}]]></Description>
        <PicUrl><![CDATA[{{$article->picUrl}}]]></PicUrl>
        <Url><![CDATA[{{$article->url}}]]></Url>
    </item>
    @endforeach
</Articles>
@stop