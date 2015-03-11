<?php namespace ApiBundle\Services;
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 11.03.2015
 * Time: 08:06
 */

class XmlParser
{
    /**
     * @var array
     */
    protected $fields = array(
        'title',
        'description',
        'link',
        'image',
        'publishedDate'
    );

    private $cache = false;

    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    function __construct(array $config = array())
    {
        $this->config = $config;
    }

    /**
     * @param int $limit
     * @return array|bool
     */
    public function readFeed($limit = 1)
    {
        if ($this->cache !== false) {
            return $this->cache;
        }

        $this->cache = $this->loadArticlesFromFeed($limit);

        return $this->cache;
    }

    /**
     * @param int $limit
     * @return array
     */
    protected function loadArticlesFromFeed($limit = 1)
    {
//        $rss = simplexml_load_file();
        $count = 0;
        $articles = array();

        if (!$rss || empty($rss->channel->item)) {
            return array();
        }

        foreach ($rss->channel->item as $item) {
            if ($count == $limit) {
                break;
            }
            $articles[] = $this->loadFields($item);

            $count ++;
        }

        return $articles;
    }

    protected function loadFields($xml)
    {
        $element = array();
        foreach ($this->fields as $field) {
            $element[$field] = $this->{"get".ucfirst($field)}($xml);
        }

        return $element;
    }

    protected function getTitle($xml)
    {
        return (string) $xml->title;
    }

    protected function getLink($xml)
    {
        return (string) $xml->guid;
    }

    protected function getPublishedDate($xml)
    {
        return (string) $xml->pubDate;
    }

    protected function getDescription($xml)
    {
        return (string) $xml->description;
    }

    protected function getImage($xml)
    {
        return (string) $xml->enclosure['url'];
    }
}