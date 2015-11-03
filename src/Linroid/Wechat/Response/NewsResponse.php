<?php
/**
 * Created by PhpStorm.
 * User: linroid
 * Date: 14-5-24
 * Time: 下午7:31
 */

namespace Linroid\Wechat\Response;


class NewsResponse extends AbstractResponse{
    public $msgType = 'news';
    public $articles = array();
    public function addArticle(Article $article) {
        array_push($this->articles, $article);
    }
    public function getCount(){
        return count($this->articles);
    }
}
class Article{
    public $title;
    public $description;
    public $picUrl;
    public $url;

    function __construct($title, $description, $picUrl, $url)
    {
        $this->title = $title;
        $this->description = $description;
        $this->picUrl = $picUrl;
        $this->url = $url;
    }

}