<?php

namespace Troiswa\BackBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {

        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/login');

        //$this->assertTrue(true);

        $this->assertEquals(1, $crawler->filter('html:contains("Username")')->count());

    }
}
