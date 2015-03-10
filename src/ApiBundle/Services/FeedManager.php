<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 10.03.2015
 * Time: 08:47
 */

namespace ApiBundle\Services;


class FeedManager
{

    protected $limit = 20;
    protected $orderBy = 'title';
    protected $fields = array(
        'title',
        'description',
        'link',
        'image'
    );

    public function __construct()
    {
    }

    public function loadArticlesFromFeed()
    {
        $rss = simplexml_load_file('http://www.telegraph.co.uk/sport/football/competitions/premier-league/rss');
        $count = 0;
        $articles = array();
        foreach ($rss->channel->item as $item) {
            $articles[] = $this->loadFields($item);
            $count ++;
        }
    }

    protected function saveArticles()
    {

    }

    protected function sortArticles()
    {

    }

    public function checkNewArticle()
    {

    }

    public function getArticles()
    {

    }

    protected function loadFields($xml)
    {
        $element = array();
        foreach ($this->fields as $field) {
            $element[$field] = $this->get{ucfirst($field)}($xml);
        }

        return $element;
    }

    protected function getTitle($xml)
    {
        return $xml->title;
    }

    protected function getLink($xml)
    {
        return $xml->link;
    }

    protected function getDescription($xml)
    {
        return $xml->title;
    }

    protected function getImage($xml)
    {
        return $xml->enclosure->url;
    }
}