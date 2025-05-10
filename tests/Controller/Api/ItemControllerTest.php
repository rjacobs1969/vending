<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ItemControllerTest extends WebTestCase
{
    public function testGetExampleCategories(): void
    {
        $client = static::createClient();
        $response = $client->request('GET', '/api/item');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
