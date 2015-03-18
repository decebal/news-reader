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
    protected $orderBy = array('addedOn' => 'desc', 'title' => "asc");

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
    }

    public function loadArticles()
    {
        $articles = $this->articleEntity->findBy(array(), $this->orderBy, $this->limit);

        return $articles;
    }

    protected function saveArticles($articles)
    {
        if (empty($articles)) {
            return false;
        }

        foreach ($articles as $article) {
            $exists = $this->articleEntity->findOneBy(
                array('link' =>$article['link'])
            );

            if ($exists) {
                continue;
            }

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

    /**
     * @return bool
     */
    public function checkNewArticle()
    {
        $lastArticles = $this->xmlReader->readFeed($this->limit);
        if (empty($lastArticles)) {
            return false;
        }
        $lastArticle = array_shift($lastArticles);

        $article = $this->articleEntity->findOneBy(array('link' => $lastArticle['link']));

        if (is_null($article)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $source
     *
     * @return \ApiBundle\Entity\Article[]|array|bool
     */
    public function getArticles($source = 'telegraph')
    {
        $this->xmlReader->setSourceName($source);
        if ($this->checkNewArticle()) {
            $articles = $this->xmlReader->readFeed($this->limit);
            $this->saveArticles($articles);
        }
        $articles = $this->loadArticles();

        return $articles;
    }
}
