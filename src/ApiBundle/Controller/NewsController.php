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
        //read xml
//        $feed = file_get_contents('http://feeds.bbci.co.uk/news/england/rss.xml');
//        $feed = str_replace('<media:', '<', $feed);
//
//        $rss = simplexml_load_string($feed);
//


        return $this->render('default/index.html.twig');
    }
}