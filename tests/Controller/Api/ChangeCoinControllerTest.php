<?php

namespace App\Tests\Controller\Api;

use App\Application\ViewModel\ChangeCoin\ChangeCoinListViewModel;
use App\Application\ViewModel\ChangeCoin\ChangeCoinViewModel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ChangeCoinControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient(['environment' => 'test']);
    }

    public function testSetChangeCoinQuantity(): void
    {
        $this->doRequest("/api/service/change", json_encode(["coin" => 0.25, "quantity" => 33]), 'PUT');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey(ChangeCoinViewModel::FIELD_COIN, $response);
        $this->assertArrayHasKey(ChangeCoinViewModel::FIELD_QUANTITY, $response);
        $this->assertArrayHasKey(ChangeCoinViewModel::FIELD_VALUE, $response);
        $this->assertEquals(0.25, $response[ChangeCoinViewModel::FIELD_COIN]);
        $this->assertEquals(33, $response[ChangeCoinViewModel::FIELD_QUANTITY]);
        $this->assertEquals(8.25, $response[ChangeCoinViewModel::FIELD_VALUE]);
    }

    public function testListChangeCoinQuantities(): void
    {
        $this->doRequest("/api/change", '', 'GET');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey(ChangeCoinListViewModel::FIELD_COINS, $response);
        $this->assertIsArray($response[ChangeCoinListViewModel::FIELD_COINS]);
        $responseCoinArray = $response[ChangeCoinListViewModel::FIELD_COINS];
        $this->assertCount(3, $responseCoinArray);
        $this->assertArrayHasKey(ChangeCoinViewModel::FIELD_COIN, $responseCoinArray[0]);
        $this->assertEquals(0.25, $responseCoinArray[0][ChangeCoinViewModel::FIELD_COIN]);
    }

    private function doRequest(string $url, string $content, string $requestMethod = 'POST'): void
    {
        $this->client->request(
            $requestMethod,
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $content
        );
    }

}
