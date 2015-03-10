<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 10.03.2015
 * Time: 08:47
 */

namespace ApiBundle\Services;

use ApiBundle\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

class FeedManager
{

    protected $limit = 20;
    protected $orderBy = array('title' => "asc");

    protected $fields = array(
        'title',
        'description',
        'link',
        'image',
        'publishedDate'
    );

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    private $articleEntity;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->articleEntity = $this->entityManager->getRepository('ApiBundle:Article');
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

        $this->saveArticles($articles);

        return $articles;
    }

    public function loadArticles()
    {
        $articles = $this->articleEntity->findBy(array(), $this->orderBy, $this->limit);
        if (count($articles) < $this->limit) {
            $articles = array_merge($this->loadArticlesFromFeed(), $articles);
        }

        return $articles;
    }

    protected function saveArticles($articles)
    {
        foreach ($articles as $article) {
            $newArticle = new Article();
            $newArticle->setTitle($article['title']);
            $newArticle->setDescription($article['description']);
            $newArticle->setImagePath($article['image']);
            $newArticle->setLink($article['link']);
            $newArticle->setAddedOn(new \DateTime("now"));
            $this->entityManager->persist($newArticle);
        }
        $this->entityManager->flush();
    }

    protected function sortArticles()
    {

    }

    public function checkNewArticle()
    {

    }

    public function getArticles()
    {
        $articles = $this->loadArticles();

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
