<?php namespace ApiBundle\Services;
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 11.03.2015
 * Time: 08:06
 */

use Psr\Log\LoggerInterface;

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

    /**
     * @var bool|array
     */
    private $cache = false;

    /**
     * @var array
     */
    private $config;

    /**
     * @var string FeedSource
     */
    private $sourceName = '';
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param array $config
     * @param LoggerInterface $logger
     */
    function __construct(array $config = array(), LoggerInterface $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @param int $limit
     * @return array|bool
     */
    public function readFeed($limit)
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
        libxml_use_internal_errors(true);
        $rss = simplexml_load_file($this->config[$this->sourceName]);
        if ($rss === false) {
            foreach(libxml_get_errors() as $error) {
                $this->logger->error($error->message);
            }

            return array();
        }

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

    /**
     * @param $xml
     * @return array
     */
    protected function loadFields($xml)
    {
        $element = array();
        foreach ($this->fields as $field) {
            $element[$field] = $this->{"get".ucfirst($field)}($xml);
        }

        return $element;
    }

    /**
     * @param string $sourceName
     */
    public function setSourceName($sourceName)
    {
        $this->sourceName = $sourceName;
    }

    /**
     * @param $xml
     * @return string
     */
    protected function getTitle($xml)
    {
        return (string) $xml->title;
    }

    /**
     * @param $xml
     * @return string
     */
    protected function getLink($xml)
    {
        return (string) $xml->guid;
    }

    /**
     * @param $xml
     * @return string
     */
    protected function getPublishedDate($xml)
    {
        return (string) $xml->pubDate;
    }

    /**
     * @param $xml
     * @return string
     */
    protected function getDescription($xml)
    {
        return (string) $xml->description;
    }

    /**
     * @param $xml
     * @return string
     */
    protected function getImage($xml)
    {
        return (string) $xml->enclosure['url'];
    }
}