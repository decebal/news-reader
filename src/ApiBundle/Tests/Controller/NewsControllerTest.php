<?php
/**
 * Created by PhpStorm.
 * User: decebal
 * Date: 10.03.2015
 * Time: 07:18
 */

namespace ApiBundle\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NewsControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/app/example');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Homepage")')->count() > 0);
    }
}