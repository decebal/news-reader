<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 10.03.2015
 * Time: 07:17
 */

namespace ApiBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NewsController extends Controller
{

    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    public function feedReaderAction(Request $request)
    {
        $feedManager = $this->get('apibundle.services.feed_reader');
        $viewParams['articles'] = $feedManager->getArticles($request->get('feed'));

        return $this->render('default/feed.html.twig', $viewParams);
    }
}