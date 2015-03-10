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
        return $this->render('index.twig');
    }
}