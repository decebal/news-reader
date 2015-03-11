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

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    private $articleEntity;

    /**
     * @var XmlParser
     */
    private $xmlReader;

    public function __construct(EntityManagerInterface $entityManager, XmlParser $xmlReader)
    {
        $this->entityManager = $entityManager;
        $this->articleEntity = $this->entityManager->getRepository('ApiBundle:Article');
        $this->xmlReader = $xmlReader;
        var_dump($this->xmlReader);
        die();
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
}
