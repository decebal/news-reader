<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 10.03.2015
 * Time: 07:17
 */

namespace ApiBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NewsController extends Controller
{

    public function indexAction()
    {
        $feedManager = $this->get('apibundle.services.feed_reader');
        $viewParams['articles'] = $feedManager->getArticles();

        return $this->render('default/index.html.twig', $viewParams);
    }
}